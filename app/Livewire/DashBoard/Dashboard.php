<?php

namespace App\Livewire\DashBoard;

use Carbon\Carbon;
use App\Models\Note;
use Livewire\Component;
use App\Models\Log as Logs;
use Livewire\Attributes\On;
use App\Models\Reservations;
use Livewire\WithPagination;
use Livewire\Attributes\Lazy;
use App\Services\CalendarService;
use Illuminate\Support\Facades\DB;
use Livewire\WithoutUrlPagination;
use App\Mail\Reservation\TermsMail;
use Illuminate\Support\Facades\Log;
use App\Mail\Reservation\RefundMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\Reservation\CancelledMail;
use App\Mail\Reservation\ReservationMail;
use App\Mail\ReservationReassignmentMail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\Reservation\GiftReservationMail;
use Intervention\Image\Laravel\Facades\Image;

#[Lazy]
class Dashboard extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $listeners = ['refreshDash' => '$refresh'];
    public $search = '';

    public $reservation_id;

    public $status, $msj;

    public $rnombre,
        $rapellidos,
        $rtelefono,
        $remail;

    public $dateResUp, $timeResUp;

    public $observations;

    public $cnombre, $cemail, $gfnombre, $gfemail;

    protected $calendar;

    public function boot(CalendarService $calendar)
    {
        $this->calendar = $calendar;
    }

    public function placeholder()
    {
        return view('livewire.placeholder.load-component');
    }
    public function render()
    {
        $reservations = Reservations::/* with('carros')-> */when($this->search, function ($query) {
            $query->where('id', 'like', '%' . $this->search . '%')
                ->orWhere('nombre', 'like', '%' . $this->search . '%')
                ->orWhere('apellidos', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('telefono', 'like', '%' . $this->search . '%')
                ->orWhere('fecha_reservacion', 'like', '%' . $this->search . '%')
                ->orWhere('hora_reservacion', 'like', '%' . $this->search . '%');
        })
            ->select('id', 'nombre', 'apellidos', 'email', 'telefono', 'fecha_reservacion', 'hora_reservacion', 'created', 'total', 'estatus')
            ->orderBy('id', 'DESC')
            ->paginate(10, pageName: 'pageReservations');
        // dd($reservations);

        return view('livewire.dash-board.dashboard', compact('reservations'));
    }

    public function addNote()
    {
        $this->resetValidation();

        $this->validate([
            'msj' => 'required',
            'status' => 'required'
        ]);
        try {
            Reservations::find($this->reservation_id)->update([
                'note' => $this->msj,
                'estatusfintour' => $this->status
            ]);
            $this->reset('reservation_id', 'status', 'msj');
            $this->dispatch('notify', msj: 'Se asigno correctamente el status', type: 'success', method: 'addNote');
        } catch (\Exception $e) {
            throw $e;
            Log::error("Error al agregar una nota a la reservacion {$this->reservation_id} - {$e}");
            $this->dispatch('notify', msj: 'Hubo un error por favor intentelo mas tarde', type: 'error', method: 'addNote');
        }
    }

    #[On('show-reservation-modal')]
    public function shoReservation($reservation_id)
    {
        $reservation = Reservations::select(
            'id',
            'nombre',
            'apellidos',
            'telefono',
            'email',
            'estatusfintour',
            'fecha_reservacion',
            'hora_reservacion',
            'created',
            'total',
            'note',
            'terminos',
            'licensia'
        )
            ->with([
                'carros:id,nombre', // selecciona solo ciertos campos de la relación
                'persons:id,id_reserva,persons', // selecciona solo ciertos campos de la relación
                'note.user' // selecciona solo ciertos campos de la relación
            ])
            ->find($reservation_id);
        // dd($reservation);
        $this->dispatch('show-modal-reservation', reservation: $reservation);
    }

    public function updateCustomer()
    {
        $this->resetValidation();

        try {
            $this->validate(
                [
                    'rnombre' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                    'rapellidos' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                    'rtelefono' => 'required|regex:/^\+?[0-9]{10,15}$/',
                    'remail' => 'required|email'
                ],
                [
                    'rnombre.required' => 'El nombre es obligatorio.',
                    'rnombre.regex' => 'El nombre solo puede contener letras y espacios.',
                    'rapellidos.required' => 'Los apellidos son obligatorios.',
                    'rapellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
                    'rtelefono.required' => 'El teléfono es obligatorio.',
                    'rtelefono.regex' => 'El teléfono debe contener entre 10 y 15 dígitos y puede incluir un prefijo de país opcional.',
                    'remail.required' => 'El correo electrónico es obligatorio.',
                    'remail.email' => 'El correo electrónico debe ser una dirección válida.',
                ]
            );
            Reservations::find($this->reservation_id)->update([
                'nombre' => $this->rnombre,
                'apellidos' => $this->rapellidos,
                'telefono' => $this->rtelefono,
                'email' => $this->remail
            ]);
            $this->dispatch('notify', msj: 'Se actualizo correctamente el cliente', type: 'success', method: 'updateCustomer');
            $this->reset('reservation_id', 'rnombre', 'rapellidos', 'rtelefono', 'remail');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', errors: $e->errors(), type: 'error', method: 'errorValidationFormCustomer');
        } catch (\Exception $e) {
            Log::error("Error al actualizar el cliente {$this->reservation_id} - {$e}");
            $this->dispatch('notify', msj: 'Hubo un error por favor intentelo mas tarde', type: 'error', method: 'updateCustomer');
        }
    }

    public function updateDateReservation()
    {

        $reservation = Reservations::with('carros', 'persons')->find($this->reservation_id);

        $vehicleId = $reservation->carros->pluck('id')->unique()->values();

        $availability = DB::table('carros as c')
            ->select(
                'c.id',
                'c.nombre',
                'c.disponible AS inventory',
                DB::raw("COALESCE(SUM(
                        CASE WHEN r.fecha_reservacion = '$this->dateResUp'
                            AND r.hora_reservacion = '$this->timeResUp'
                            AND r.estatus = 1
                        THEN cr.total_reservas
                        ELSE 0
                        END
                        ), 0) AS total_reserves"),
                DB::raw("c.disponible - COALESCE(SUM(
                            CASE WHEN r.fecha_reservacion = '$this->dateResUp'
                                AND r.hora_reservacion = '$this->timeResUp'
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
            // ->rightJoin('reservaciones as r', 'r.id', 'cr.id_reserva')
            ->where('c.estatus', 1)
            ->whereIn('c.id', $vehicleId)
            ->where('c.location', 4)
            ->where('c.servicio', 0)
            ->groupBy('c.id')
            ->get()
            ->keyBy('id');

        // Recorre cada vehículo para verificar su disponibilidad en la fecha y hora seleccionada
        foreach ($availability as $key => $value) {
            // Busca el vehículo correspondiente en la reserva actual
            $carro = $reservation->carros->firstWhere('id', $key);
            if (
                $value->availability <= 0 &&
                $carro->pivot->total_reservas > abs($value->availability)
            ) {
                $this->dispatch(
                    'notify',
                    msj: "El vehiculo {$value->nombre} no esta disponible para la fecha y hora seleccionada",
                    type: 'error',
                    method: 'errorUpdateDateReservation'
                );
                return;
            }
        }
        try {
            $res = DB::transaction(function () use ($reservation) {
                // Actualiza la fecha y hora de la reserva
                $reservation->update([
                    'fecha_reservacion' => $this->dateResUp,
                    'hora_reservacion' => $this->timeResUp
                ]);
                $this->eventCalendar($reservation, $reservation->carros);;

                return $reservation;
            });
            try {
                Mail::to('alopez@beneficiosvacacionales.mx')->send(new ReservationReassignmentMail($res));
            } catch (\Exception $e) {
                Log::error("Error al enviar el correo de reasignación de reservación {$reservation->id} - {$e}");
                throw $e;
            }
            $this->reset('reservation_id', 'dateResUp', 'timeResUp');
            $this->dispatch('notify', msj: 'Se actualizo correctamente la fecha y hora de la reservacion', type: 'success', method: 'updateDateReservation');
        } catch (\Exception $e) {
            throw $e;
            Log::error("Error al actualizar la fecha y hora de la reservación {$this->reservation_id} - {$e}");
            $this->dispatch('notify', msj: 'Hubo un error por favor intentelo mas tarde', type: 'error', method: 'errorUpdateDateReservation');
        }
    }

    protected function eventCalendar($reservation, $vehicles)
    {
        try {
            $carSms = $vehicles->map(function ($car) {
                return "$car->nombre ({$car->pivot->total_reservas})";
            })->implode(', ');

            $vehicleInCar = $vehicles->contains('id', 21);
            $endTime = Carbon::parse($this->timeResUp);
            if ($vehicleInCar) {
                $endTime->addHour(1)->addMinutes(30);
            } else {
                $endTime->addHour(2);
            }
            // dd($endTime->format('H:i:s'), 'endTime');
            $data = [
                'asunto' => "Reserva de $reservation->nombre $reservation->apellidos folio: $reservation->id",
                'descripcion' => "A nombnre de $reservation->nombre $reservation->apellidos Número telefónico: $reservation->telefono, Los vehículos que reservó fueron: $carSms",
                'inicio' => "{$this->dateResUp}T{$this->timeResUp}:00",
                'fin' => "{$this->dateResUp}T{$endTime->format('H:i:s')}",
            ];

            $calendar = $this->calendar->updateEvent($reservation->id_calendar, $data);
            return $calendar;
        } catch (\Exception $e) {
            Log::error("Error al actualizar el evento en el calendario para la reservacion {$reservation->id} - {$e}");
            throw $e->getMessage();
        }
    }

    #[On('resendMailReservation')]
    public function resendMail($reservation_id)
    {
        // dd($reservation_id);
        try {
            $reservation = Reservations::find($reservation_id);
            if (!$reservation) {
                $this->dispatch('notify', msj: 'Reservación no encontrada', type: 'error', method: 'resendMail');
                return;
            }
            Mail::to('alopez@beneficiosvacacionales.mx')->send(new ReservationMail($reservation));
            $this->dispatch('notify', msj: 'Correo reenviado correctamente', type: 'success', method: 'resendMail');
        } catch (\Exception $e) {
            Log::error("Error al reenviar el correo de la reservación {$reservation_id} - {$e}");
            $this->dispatch('notify', msj: 'Hubo un error al reenviar el correo, por favor intente más tarde.', type: 'error', method: 'resendMail');
        }
    }

    #[On('sendMailRefund')]
    public function mailRefund($reservation_id)
    {
        try {
            $reservation = Reservations::find($reservation_id);
            if (!$reservation) {
                $this->dispatch('notify', msj: 'Reservación no encontrada', type: 'error', method: 'sendMailRefund');
                return;
            }
            Mail::to('alopez@beneficiosvacacionales.mx')->send(new RefundMail($reservation));
            $this->dispatch('notify', msj: 'Correo reenviado correctamente al cliente', type: 'success', method: 'sendMailRefund');
        } catch (\Exception $e) {
            Log::error("Error al reenviar el correo de reembolso de la reservación {$reservation_id} - {$e}");
            $this->dispatch('notify', msj: 'Hubo un error al reenviar el correo, por favor intente más tarde.', type: 'error', method: 'sendMailRefund');
        }
    }

    #[On('resendTerms')]
    public function resendTerms($reservation_id)
    {
        try {
            $reservation = Reservations::find($reservation_id);
            if (!$reservation) {
                $this->dispatch('notify', msj: 'Reservación no encontrada', type: 'error', method: 'resendTerms');
                return;
            }
            Mail::to('alopez@beneficiosvacacionales.mx')->send(new TermsMail($reservation));
            $this->dispatch('notify', msj: 'Correo reenviado correctamente al cliente', type: 'success', method: 'resendTerms');
        } catch (\Exception $e) {
            Log::error("Error al reenviar los términos y condiciones de la reservación {$reservation_id} - {$e}");
            $this->dispatch('notify', msj: 'Hubo un error al reenviar el correo, por favor intente más tarde.', type: 'error', method: 'resendTerms');
        }
    }

    public function addObservations()
    {
        $this->resetValidation();

        $this->validate([
            'observations' => 'required|string|max:500',
        ], [
            'observations.required' => 'Las observaciones son obligatorias.',
            'observations.string' => 'Las observaciones deben ser un texto.',
            'observations.max' => 'Las observaciones no pueden exceder los 500 caracteres.',
        ]);

        try {
            $note = DB::transaction(function () {
                $notes = Note::create(
                    [
                        'id_user' => auth()->user()->id,
                        'note' => $this->observations
                    ]
                );
                $notes->reservation()->associate(Reservations::find($this->reservation_id));
                return $notes->save();
            });
            if (!$note) {
                $this->dispatch('notify', msj: 'No se pudo agregar la observación', type: 'error', method: 'addObservations');
                return;
            }
            $this->dispatch('notify', msj: 'Se agregaron las observaciones correctamente', type: 'success', method: 'addObservations');
            $this->reset('reservation_id', 'observations');
        } catch (\Exception $e) {
            Log::error("Error al agregar observaciones a la reservación {$this->reservation_id} - {$e}");
            $this->dispatch('notify', msj: 'Hubo un error por favor intentelo mas tarde', type: 'error', method: 'addObservations');
        }
    }

    public function generateQr()
    {
        $this->resetValidation();
        $this->validate([
            'cnombre' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'cemail' => 'required|email',
            'gfnombre' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'gfemail' => 'required|email',
        ], [
            'cnombre.required' => 'El nombre es obligatorio.',
            'cnombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'cemail.required' => 'El correo electrónico es obligatorio.',
            'cemail.email' => 'El correo electrónico debe ser una dirección válida.',
            'gfnombre.required' => 'El nombre del regalo es obligatorio.',
            'gfnombre.regex' => 'El nombre del regalo solo puede contener letras y espacios.',
            'gfemail.required' => 'El correo electrónico del regalo es obligatorio.',
            'gfemail.email' => 'El correo electrónico del regalo debe ser una dirección válida.',
        ]);

        $reserv = DB::transaction(function () {
            try {
                $reservation = Reservations::with('carros', 'card', 'coupon')->find($this->reservation_id);

                if (!$reservation) {
                    throw new \Exception("Reservación original no encontrada");
                }

                // Duplicar la reservación
                $duplicateReservation = $reservation->replicate();
                $duplicateReservation->nombre = $this->cnombre;
                $duplicateReservation->apellidos = "";
                $duplicateReservation->email = $this->cemail;
                $duplicateReservation->name_gift = $this->gfnombre;
                $duplicateReservation->mail_gift = $this->gfemail;
                $duplicateReservation->estatus = 8;
                $duplicateReservation->fecha_reservacion = null;
                $duplicateReservation->hora_reservacion = null;
                $duplicateReservation->created = now();
                $duplicateReservation->save();

                // Duplicar relación carros
                foreach ($reservation->carros as $car) {
                    $duplicateReservation->carros()->attach($car->id, [
                        'total_reservas' => $car->pivot->total_reservas,
                        'is_delete' => 0,
                        'total_cobrar' => $car->pivot->total_cobrar,
                        // agrega aquí otros campos del pivot si existen
                    ]);
                }

                // Duplicar relación card
                if ($reservation->card) {
                    $newCard = $duplicateReservation->card()->create($reservation->card->toArray());
                    if (!$newCard) {
                        throw new \Exception("No se pudo duplicar la tarjeta");
                    }
                }

                // Duplicar relación coupon
                if ($reservation->coupon) {
                    $newCoupon = $duplicateReservation->coupon()->create($reservation->coupon->toArray());
                    if (!$newCoupon) {
                        throw new \Exception("No se pudo duplicar el cupón");
                    }
                }

                // Cancelar la reserva original
                try {
                    $this->cancelReservation($reservation->id);
                } catch (\Exception $e) {
                    Log::error("Error al cancelar la reservación original {$this->reservation_id} - {$e}");
                    throw $e;
                }

                // Generar y enviar QR
                $this->generateImageQr($duplicateReservation);
                try {
                } catch (\Exception $e) {
                    Log::error("Error al enviar el correo de reserva duplicada {$duplicateReservation->id} - {$e}");
                    throw $e;
                }


                return [
                    'msj' => 'Se generó el QR y se envió el correo correctamente',
                    'method' => 'generateQr',
                    'type' => 'success',
                    'reservation' => $duplicateReservation
                ];
            } catch (\Exception $e) {
                Log::error("Error al generar el QR y enviar el correo de reserva duplicada - {$e}");
                return [
                    'msj' => 'Hubo un error al generar el QR y enviar el correo, por favor intente más tarde.',
                    'method' => 'generateQr',
                    'type' => 'error'
                ];
            }
        });

        $this->dispatch('notify', msj: $reserv['msj'], type: $reserv['type'], method: 'generateQr');
        $this->reset('reservation_id', 'cnombre', 'cemail', 'gfnombre', 'gfemail');
        // dd($reserv);
    }

    protected function cancelReservation($reservation_id)
    {
        $reservation = Reservations::find($reservation_id);
        if (!$reservation) {
            $this->dispatch('notify', msj: 'Reservación no encontrada', type: 'error', method: 'cancelReservation');
            return;
        }
        try {
            DB::beginTransaction();
            $reservation->update(['estatus' => 2]);
            foreach ($reservation->carros as $car) {
                // $car->pivot->is_delete = 1; // Marca el carro como eliminado
                // $car->pivot->save();
                $reservation->carros()->updateExistingPivot($car->id, ['is_delete' => 1]);
            }
            if ($reservation->id_calendar) {
                $this->calendar->deleteEvent($reservation->id_calendar);
            }
            try {
                Mail::to('alopez@beneficiosvacacionales.mx')->send(new CancelledMail($reservation));
            } catch (\Exception $e) {
                Log::error("Error al enviar el correo de cancelación de reservación {$reservation->id} - {$e}");
                throw $e;
            }

            $log = Logs::create([
                'movimiento' => 3,
                'fecha_movimento' => now(),
            ]);
            $log->reservation()->associate($reservation);
            $log->user_id = auth()->id();
            $log->save();
            DB::commit();
            return $reservation;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al cancelar la reservación {$reservation_id} - {$e}");
            throw $e;
        }
    }

    protected function generateImageQr($reservation)
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

    #[On('cancelled-onli-reservation')]
    public function cancelled($reservation_id)
    {
        // dd($reservation_id, 'cancelled');
        try {
            $reservation = Reservations::find($reservation_id);
            if (!$reservation) {
                $this->dispatch('notify', msj: 'Reservación no encontrada', type: 'error', method: 'cancelled');
                return;
            }
            $this->cancelReservation($reservation_id);
            $this->dispatch('notify', msj: 'Reservación cancelada correctamente', type: 'success', method: 'cancelled');
        } catch (\Exception $e) {
            Log::error("Error al cancelar la reservación {$reservation_id} - {$e}");
            $this->dispatch('notify', msj: 'Hubo un error al cancelar la reservación, por favor intente más tarde.', type: 'error', method: 'cancelled');
        }
    }
}
