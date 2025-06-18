<?php

namespace App\Livewire\Vehicle;

use Carbon\Carbon;
use App\Models\Cars;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\CarService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Vehicles extends Component
{

    protected $listeners = ['refreshVehicles' => '$refresh'];

    public $vehicles = [];
    public $hour, $date;

    public  $qty = 1;
    public $guestVehicle = [];

    protected $carService;

    public function boot(CarService $carService)
    {
        $this->carService = $carService;
    }

    public function render()
    {
        // dd($this->vehicles);
        return view('livewire.vehicle.vehicles', [
            'vehicles' => $this->vehicles,
        ]);
    }

    public function addVehicle($reservation_id)
    {
        $this->resetValidation();
        try {
            Validator::make(
                ['qty' => $this->qty, 'guestVehicle' => $this->guestVehicle],
                ['qty' => 'required|numeric|not_in:0', 'guestVehicle' => ($reservation_id !== 21 ? 'required|array' : 'nullable'), 'guestVehicle*' => ($reservation_id !== 21 ? 'required|not_in:0' : 'nullable')],
                // ['required' => '']
            )->validate();
            $available = DB::table('reservaciones AS r')
                ->select(
                    'c.id',
                    'c.disponible AS inventario',
                    DB::raw('IFNULL(SUM(cr.total_reservas), 0) AS total_reservas'),
                    DB::raw('c.disponible - IFNULL(SUM(cr.total_reservas), 0) AS disponible'),
                    'c.nombre',
                    'c.precio',
                    'c.img_vehiculo'
                )
                ->leftJoin('carros_reservados AS cr', function ($query) {
                    $query->on('cr.id_reserva', 'r.id')
                        ->where('r.fecha_reservacion', $this->date)
                        ->where('r.hora_reservacion', $this->hour);
                })
                ->rightJoin('carros as c', 'c.id', 'cr.id_carro')
                ->where('c.estatus', 1)
                ->where('c.id', $reservation_id)
                ->groupBy('id')
                ->first();

            $day = Carbon::parse($this->date)->locale('es')->translatedFormat('l');
            $price = /* in_array($day, ['lunes', 'martes', 'miercoles', 'jueves']) ? 99.99 :  */ $available->precio;
            if ($this->qty > $available->disponible) {
                throw new \Exception('Por favor, seleccione el numero de vehículos a reservar.', 1);
            }
            $totalGuest = 0;
            $vehicleGuest = [];
            if (isset($this->guestVehicle) && is_array($this->guestVehicle)) {
                foreach ($this->guestVehicle as $key => $value) {
                    $priceGuest = match ($value) {
                        3 => 50,
                        4 => 100,
                        default => 0,
                    };
                    $total = $available->precio + $priceGuest;
                    $totalGuest += $priceGuest;

                    $vehicleGuest[$key] = [
                        'car' => $key,
                        'product_id' => $reservation_id,
                        'guest' => $value,
                        'priceGuest' => $priceGuest,
                        'total' => $total,
                    ];
                }
            }

            $vehicle = Cars::find($reservation_id);
            $amount = $price * $this->qty + $totalGuest;

            $vehicle->precio = $price;
            $vehicle->available = $this->qty;
            $vehicle->amount = $amount;
            $vehicle->totalGuest = $totalGuest;
            $vehicle->total = $amount;

            $data = [
                'car' => $vehicle,
                'price' => $price,
                'reservation' => ['carGuest' => $vehicleGuest, 'totalGuest' => $totalGuest],
                'date' => $this->date,
                'hour' => $this->hour,
                'available' => $this->qty,
                'is_gift' => 0
            ];

            $this->carService->add($data);

            $this->reset(
                'qty',
                'guestVehicle'
            );
            $this->dispatch('refreshVehicles');
            $this->dispatch('notify', msj: "Se agrego correctamente el vehiculo", type: 'success', method: 'addVehicle', vehicle: $reservation_id);

            // dd($this->qty, $this->guestVehicle, $this->date, $this->hour, $reservation_id, $available, 'vehicle');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', errors: $e->errors(), type: 'error', method: 'errorValidationAddVehicle');
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . " - Line: " . $e->getLine());
            $this->dispatch(
                'notify',
                type: 'error',
                msj: 'Error al agregar el vehiculo: ' . $e->getMessage(),
                method: 'addVehicle'
            );
        }
    }

    public function removeGuestVehicleItem($index)
    {
        // dd($this->guestVehicle);
        if (array_key_exists($index, $this->guestVehicle)) {
            // dd($index, $this->guestVehicle);
            unset($this->guestVehicle[$index]);
        }
    }
}
