<?php

namespace App\Livewire\Reservations;

use Exception;
use Carbon\Carbon;
use App\Models\Code;
use Livewire\Component;
use App\Models\Registers;
use Livewire\Attributes\On;
use App\Models\Reservations;
use App\Models\ReservedCars;
use App\Services\CarService;
use App\Services\NetelipSms;
use Livewire\Attributes\Lazy;
use App\Models\VehiclesPerson;
use App\Models\ReservationCode;
use App\Models\ReservationType;
use App\Services\CalendarService;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use App\Mail\Reservation\TermsMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Reservation\ReservationMail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\Reservation\GiftReservationMail;
use Intervention\Image\Laravel\Facades\Image;

#[Lazy]
class Order extends Component
{
    protected $listeners = ['refreshPayment' => '$refresh'];
    public $type;

    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $platform;
    public $infoCustomer;
    public $beneficiary_name;
    public $beneficiary_mail;

    public $pay = 1;
    public $is_gift = false;
    public $dataCuestomer;
    public $payment_token;


    protected $carService;
    protected $calendar;
    protected $sms;
    public $items;




    // #[Validate('required|string|max:255')]
    public $coupon;

    public function boot(CarService $carService, CalendarService $calendar, NetelipSms $sms)
    {
        $this->carService = $carService;
        $this->items = $this->carService->getCar();
        $this->calendar = $calendar;
        $this->sms = $sms;
        // session()->forget('cli');
        $this->infoCustomer = session()->has('cli') ? true : false;
        $this->dataCuestomer = session()->has('cli') ? session()->get('cli') : [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email
        ];
    }

    public function placeholder()
    {
        return view('livewire.placeholder.load-component');
    }

    public function render()
    {
        $this->type = ReservationType::all();
        return view('livewire.reservations.order');
    }

    public function formInfoCustomer()
    {
        $this->resetValidation();
        try {
            $this->validate([
                'name' => 'required|string',
                'last_name' => 'required|string',
                'phone' => 'required|regex:/^\+?[0-9]{9,15}$/',
                'email' => 'required|email',
                'platform' => 'required|not_in:0',
                'beneficiary_name' => $this->is_gift ? 'required|string' : 'nullable',
                'beneficiary_mail' => $this->is_gift ? 'required|email' : 'nullable',
            ]);
            session()->put('cli', [
                'name' => $this->name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'platform' => $this->platform,
                'beneficiary_name' => $this->beneficiary_name,
                'beneficiary_mail' => $this->beneficiary_mail,
                'gift' => $this->is_gift
            ]);

            $this->dispatch('notify', msj: 'Se registro correctamente el usuario', type: 'success', method: 'infoCustomer');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // dd($e->errors());
            $this->dispatch('notify', errors: $e->errors(), type: 'error', method: 'errorValidationFormCustomer');
        } catch (\Throwable $th) {
            // dd($th);
            $this->dispatch('notify', msj: 'Se produjo un error al guardar el usuario', type: 'error', method: 'errorInfoCustomer');
            // dd($th);
        }
    }

    public function payment()
    {
        if ($this->pay === "0") {
            $order = $this->saveReeservationOnly();
            dd($order);
            # code...
        }
        // dd($this->pay);
        if ($this->pay == "1") {
            $this->dispatch('processPaymentMethod');
        }
    }

    public function addCoupon()
    {
        // dd($this->coupon);
        $this->validate([
            'coupon' => 'required|string|max:255'
        ]);
        try {
            $code = Code::where('codigo', $this->coupon)->where('estatus', 1)->first();
            // dd($code);
            if (!$code) {
                throw new Exception("Cupon no encontrado o invalido", 1);
            }
            $validate = Carbon::parse($code->valides);
            if ($validate->isPast()) {
                throw new Exception("El cupón ha expirado.", 1);
            }
            $this->carService->addCode($code->id);

            $this->dispatch('notify', msj: 'Se aplico el cupon correctamente', type: 'success', method: 'applyCoupon');
        } catch (\Exception $e) {
            Log::error("Error al aplicar cupón: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
                // Registrar el error en los logs
            ]);

            $this->dispatch('notify', msj: $e->getMessage(), type: 'error', method: 'applyCoupon');
        }
    }

    #[On('deleteCoupon')]
    public function delete($code)
    {
        // dd($code);
        try {
            $delete = $this->carService->deleteCouponS($code);
            // dd($delete);
            $this->dispatch('notify', msj: 'Se elimino el cupon de descuento', type: 'success', method: 'deleteCoupon');
        } catch (\Exception $e) {
            $this->dispatch('notify', msj: $e->getMessage(), type: 'error', method: 'deleteCoupon');
        }
    }

    #[On('paymentMethodCreated')]
    public function paymentOrder($paymentToken)
    {
        try {
            if (config('secret.active') != true) {
                $this->dispatch('notify', msj: 'Se ha producido un error al procesar el pago, inténtelo de nuevo más tarde.', method: 'deactivatedPayments', reply: 'warning');
                return;
            }
            $subtotal = $this->items->sum('total');
        } catch (\Throwable $th) {
            //throw $th;
        }
        dd($paymentToken);
    }

    public function saveReeservationOnly()
    {
        $cli = session('cli');
        $date = $this->items->pluck('date')->first();
        $hour = $this->items->pluck('hour')->first();

        try {
            $available = $this->validateProduct($date, $hour);

            if ($available['success'] == false) {
                // Puedes lanzar una excepción o retornar el array de error
                throw new \Exception($available['message']);
            }
            $order = DB::transaction(function () use ($cli, $date, $hour) {
                $coupon = $this->items->pluck('codigo_id')->first();
                $isGift = $this->items->pluck('is_gift')->first();
                // Aquí iría la lógica para guardar la reserva
                // Retorna algún dato relevante si es necesario
                // dd($cli);
                $customer = Reservations::create([
                    'nombre' => $cli['name'],
                    'apellidos' => $cli['last_name'],
                    'email' => $cli['email'],
                    'telefono' => $cli['phone'],
                    'entrada_parque' => 0,
                    'fecha_reservacion' => $cli['gift'] === "true" ? null : $date,
                    'hora_reservacion' => $cli['gift'] === "true" ? null : $hour,
                    'importe' => 0,
                    'impuesto' => 0,
                    'total' => 0,
                    'tipo_venta' => 1,
                    'id_tipor' => $cli['platform'],
                    'location' => 4,
                    'estatus' => $cli['gift'] === "true" ? 8 : 1,
                    'name_gift' => $cli['gift'] === "true" ? $cli['beneficiary_name'] : null,
                    'mail_gift' => $cli['gift'] === "true" ? $cli['beneficiary_mail'] : null,
                    'stripe_id' => null,
                ]);
                // dd($customer);

                if ($coupon !== null) {
                    ReservationCode::create([
                        'cupon_id' => $coupon,
                        'reservacion_id' => $customer->id
                    ]);
                }
                $reservedCarsData = [];
                $guestsData = [];

                $this->items->each(function ($item) use ($customer,  &$reservedCarsData, &$guestsData) {
                    $reservedCarsData[] = [
                        'id_reserva' => $customer->id,
                        'id_carro' => $item->carros->id,
                        'total_reservas' => $item->available,
                        'total_cobrar' => $item->total,
                    ];
                    if ($item->person_car) {
                        collect(json_decode($item->person_car))->each(function ($person) use ($customer, &$guestsData) {
                            $guestsData[] = [
                                'id_reserva' => $customer->id,
                                'id_carro' => $person->product_id,
                                'persons' => $person->guest,
                                'total' => $person->total,
                            ];
                        });
                    }
                });

                ReservedCars::insert($reservedCarsData);
                // Si VehiclesPerson no tiene eventos/relaciones especiales:
                VehiclesPerson::insert($guestsData);

                Registers::create([
                    'user_id' => 17,
                    'movimiento' => 1,
                ]);

                if ($customer->name_gift === null && $customer->mail_gift === null) {
                    try {
                        $event = $this->addEvent($customer, $this->items);
                        $customer->update([
                            'id_calendar' => $event->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error creating calendar event: ' . $e->getMessage());
                        throw $e;
                    }
                }

                $cupon = $coupon !== null ? $this->items->sum('total') * $this->items->pluck('code')->first()->descuento / 100 : 0;
                $descuentoTotal = $this->items->sum('total') - $cupon;

                $customer->update([
                    'importe'   => number_format($this->items->sum('total'), 2),
                    'impuesto'  => 0,
                    'total'     => $coupon === null ? number_format($this->items->sum('total'), 2) : number_format($descuentoTotal, 2),
                ]);

                return $customer;

                // dd($reservedCarsData, $guestsData);

                // return [
                //     'success' => true,
                //     'message' => 'Reserva guardada correctamente'
                // ];
            });


            // sms to customer
            try {
                $this->sms->send([
                    'phone' => $order->telefono,
                    'msj' => "Confirmación de reserva. Por favor revise su correo no deseado/spam si la confirmación no está en su bandeja de entrada.",
                ]);
            } catch (\Exception $e) {
                Log::error("Error sending SMS to customer {$e->getMessage()}");
            }

            // sms to seller
            try {
                $carSms = $this->items->map(function ($item) use ($available) {
                    // Extraer el nombre del carro y la cantidad disponible
                    $car = $available['availability']->get($item['id_vehicle']);
                    return $car ? "{$car->nombre} x{$item['quantity']}" : null;
                })->filter()->implode(',');

                $this->sms->send([
                    'phone' => config('secret.numberSeller'),
                    'msj' => "Reserva $order->id para el dia $order->fecha_reservacion a las $order->hora_reservacion vehiculos: $carSms a Nombre de $order->nombre $order->apellidos Numero: $order->telefono por api"
                ]);
            } catch (\Exception $e) {
                Log::error("Error sending SMS to seller {$e->getMessage()}");
            }

            try {
                Mail::to($order->email)->cc(config('secret.mails.mailAdmin'))->send(new ReservationMail($order));
            } catch (\Exception $e) {
                Log::error("Error sending email: {$e->getMessage()}");
                throw $e;
            }

            try {
                Mail::to($order->email)->cc(config('secret.mails.mailAdmin'))->send(new TermsMail($order));
            } catch (\Exception $e) {
                Log::error("Error sending email: {$e->getMessage()}");
                throw $e;
            }

            if ($order->name_gift && $order->mail_gift) {
                $this->generateQr($order);
            }

            app(CarService::class)->removeAllItem();

            dd($order);
        } catch (\Throwable $th) {
            // Puedes retornar el error como array para mostrarlo en la vista
            return [
                'success' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    protected function validateProduct($date, $hour)
    {
        if (!strtotime($date) || !\DateTime::createFromFormat('H:i:s', $hour)) {
            return [
                'success' => false,
                'message' => "Formato de fecha u hora inválido"
            ];
        }

        $vehicleIds = $this->items->pluck('car_id')->unique()->values();

        try {
            $availability = DB::table('carros as c')
                ->select(
                    'c.id',
                    'c.nombre',
                    'c.disponible  AS inventory',
                    DB::raw("COALESCE(SUM(
                        CASE WHEN r.fecha_reservacion = '$date'
                            AND r.hora_reservacion = '$hour'
                            AND r.estatus = 1
                        THEN cr.total_reservas
                        ELSE 0
                        END
                        ), 0) AS total_reserves"),
                    DB::raw("c.disponible - COALESCE(SUM(
                            CASE WHEN r.fecha_reservacion = '$date'
                                AND r.hora_reservacion = '$hour'
                                AND r.estatus = 1
                            THEN cr.total_reservas
                            ELSE 0
                            END
                        ), 0) AS availability")
                )
                ->leftJoin('carros_reservados  AS cr', 'c.id', 'cr.id_carro')
                ->leftJoin('reservaciones AS r', function ($q) {
                    $q->on('cr.id_reserva', 'r.id')
                        ->where('r.estatus', 1);
                })
                ->where('c.estatus', 1)
                ->whereIn('c.id', $vehicleIds)
                ->where('c.location', 4)
                ->where('c.servicio', 0)
                ->groupBy('c.id')
                ->get()
                ->keyBy('id');

            foreach ($this->items as $item) {
                $car = $availability->get($item['car_id']);
                if (!$car || (int)$car->availability < (int)$item->available) {
                    return [
                        'success' => false,
                        'message' => "Vehiculo no disponible"
                    ];
                }
            }
            return [
                'success' => true,
                'message' => 'Vehiculos disponibles',
                'availability' => $availability
            ];
        } catch (\Throwable $e) {
            $errorId = uniqid('reservation_', true);
            Log::error("[$errorId] Error creating reservation: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    protected function addEvent($reservation, $vehicle)
    {
        try {
            $carSms = collect($vehicle)
                ->map(function ($item) {
                    // Extraer el nombre del carro y la cantidad disponible
                    return "{$item['carros']['nombre']} x{$item['available']}";
                })
                ->implode(',');
            $vehicleInCar = $vehicle->contains('carros.id', 21);
            $endDate = Carbon::parse($reservation->hora_reservacion);
            if ($vehicleInCar) {
                $endDate->addHour(1)->addMinutes(30);
            } else {
                $endDate->addHour(2)/* ->addMinutes(30) */;
            }
            $data = [
                'asunto' => "Reservacion de: $reservation->nombre $reservation->apellidos folio: $reservation->id",
                'descripcion' => "A nombre de: $reservation->nombre $reservation->apellidos, Número telefónico: $reservation->telefono, Los vehículos que reservó fueron: $carSms",
                'inicio' => "{$reservation->fecha_reservacion}T{$reservation->hora_reservacion}",
                'fin' => "{$reservation->fecha_reservacion}T{$endDate->format('H:i:s')}"
            ];
            $event = $this->calendar->addEvent($data);
            return $event;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function generateQr($reservation)
    {
        $imgPath = public_path("imgs/Reserva_de_regalo_v2.png");
        $img = Image::read($imgPath);
        $nameQrImg = time() . ".png";
        $path = public_path("imgs/gifts/qr/{$nameQrImg}");
        QrCode::format('png')
            ->size(600)
            ->margin(1)
            ->errorCorrection('H')
            ->generate("https://world-adventures.es/redeem/gift/{$reservation->id}", $path);

        $img->place(
            $path,
            "bottom-right",
            1050,
            350
        );

        $nameFriend = "$reservation->nombre $reservation->apellidos";
        $img->text(
            $nameFriend,
            1650,
            2670,
            function ($font) {
                $font->file(public_path('assets/fonts/Montserrat-Black.ttf'));
                $font->size(70);
                $font->color('#000000');
                $font->align('center');
                $font->valign('top');
            }
        );
        $img->save(public_path("imgs/gifts/mail/{$nameQrImg}"));

        $data = [
            "id" => $reservation->id,
            "name" => "$reservation->nombre $reservation->apellidos",
            "qr_image" => $nameQrImg,
        ];
        try {
            Mail::to("alopez@beneficiosvacacionales.mx")->send(new GiftReservationMail($data));
        } catch (\Exception $e) {
            Log::error("Error al enviar el correo de regalo {$reservation->id} - {$e}");
            throw $e;
        }

        return [
            'name' => $nameQrImg,
            'path' => public_path("imgs/gifts/mail/{$nameQrImg}"),
        ];
    }
}
