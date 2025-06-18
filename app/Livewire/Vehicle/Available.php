<?php

namespace App\Livewire\Vehicle;

use Livewire\Component;
use App\Services\CarService;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Lazy()]
class Available extends Component
{

    protected $listeners = ['refreshAvailableVehicle' => '$refresh'];
    public $vehicles = [];
    public $date, $hour;

    public $items = [];

    protected $carService;

    public function boot(CarService $carService)
    {
        $this->carService = $carService;
        $this->items = $this->carService->getCar();
    }

    public function placeholder()
    {
        return view('livewire.placeholder.load-component');
    }

    public function render()
    {
        return view('livewire.vehicle.available');
    }

    public function availableVehicle()
    {
        $this->resetValidation();
        // dd($this->date, $this->hour);
        $this->validate([
            'date' => 'required|date_format:Y-m-d',
            'hour' => 'required|date_format:H:i'
        ], messages: [
            'date.required' => 'La fecha es obligatoria',
            'hour.required' => 'La Hora es obligatoria',
        ]);
        try {
            $available = DB::table('carros as c')
                ->select(
                    'c.id',
                    'c.nombre',
                    'c.descripcion',
                    'c.disponible AS inventory',
                    "c.precio",
                    "c.img",
                    "c.asientos",
                    DB::raw("COALESCE(SUM(
                        CASE WHEN r.fecha_reservacion = '$this->date'
                            AND r.hora_reservacion = '$this->hour'
                            AND r.estatus = 1
                        THEN cr.total_reservas
                        ELSE 0
                        END
                        ), 0) AS total_reserves"),
                    DB::raw("c.disponible - COALESCE(SUM(
                            CASE WHEN r.fecha_reservacion = '$this->date'
                                AND r.hora_reservacion = '$this->hour'
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
                // ->whereIn('c.id', $vehicleIds)
                ->where('c.location', 4)
                ->where('c.servicio', 0)
                ->groupBy('c.id')
                ->get()
                ->keyBy('id');
            // dd($available);
            $this->vehicles = $available->toArray();
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("se produjo un error en la validacion de los vehiculos: {$th->getMessage()}");
            $this->dispatch('alert', msj: 'Se produjo un error inesperado', type: 'error', method: 'validateVehicle');
        }
    }
}
