<?php

namespace App\Livewire\Vehicle;

use Livewire\Component;
use Livewire\Attributes\On;

class Vehicles extends Component
{

    protected $listeners = ['refreshVehicles' => '$refresh'];

    public $vehicles = [];

    public  $qty = 1;

    public function render()
    {
        // dd($this->vehicles);
        return view('livewire.vehicle.vehicles', [
            'vehicles' => $this->vehicles,
        ]);
    }

    #[On('addVehicle')]
    public function add()
    {

        dd($this->qty, 'vehicle');
    }
}
