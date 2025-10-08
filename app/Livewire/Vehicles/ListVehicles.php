<?php

namespace App\Livewire\Vehicles;

use App\Models\Cars;
use Livewire\Component;

class ListVehicles extends Component
{
    public function render()
    {
        $vehicles = Cars::where('location', 4)->get();
        // dd($vehicles);
        return view('livewire.vehicles.list-vehicles', [
            'vehicles' => $vehicles
        ]);
    }
}
