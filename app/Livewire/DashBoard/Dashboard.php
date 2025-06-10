<?php

namespace App\Livewire\DashBoard;

use App\Mail\ReservationReassignmentMail;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Reservations;
use Livewire\WithPagination;
use Livewire\Attributes\Lazy;
use App\Services\CalendarService;
use Illuminate\Support\Facades\DB;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
            // dd($carro);
            // Si la disponibilidad es 0 o menor, y el vehículo está en la reserva,
            // y la cantidad reservada es mayor a la disponibilidad, muestra un mensaje de error
            if (
                $value->availability <= 0 &&
                $carro &&
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

                try {
                    $this->eventCalendar($reservation, $reservation->carros);;
                } catch (\Exception $e) {
                    throw $e;
                }
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
        // Si todos los vehículos están disponibles, actualiza la reserva
        //
        // dd($this->dateResUp, $this->timeResUp, $this->reservation_id, $availability, 'updateDateReservation');
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
}
