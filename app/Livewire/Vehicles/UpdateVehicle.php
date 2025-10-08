<?php

namespace App\Livewire\Vehicles;

use App\Models\Cars;
use Livewire\Component;

class UpdateVehicle extends Component
{
    public $vehicle_id;
    public function render()
    {
        $vehicle = Cars::find($this->vehicle_id);
        return view('livewire.vehicles.update-vehicle', ['vehicle' => $vehicle]);
    }
}
