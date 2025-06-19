<?php

namespace App\Livewire\Reservations;

use Livewire\Component;
use App\Services\CarService;
use Livewire\Attributes\Lazy;

#[Lazy]
class ItemsCar extends Component
{

    protected $carService;
    public $items = [];

    public function placeholder()
    {

        return view('livewire.placeholder.load-card');
    }

    public function boot(CarService $carService)
    {
        $this->carService = $carService;
        $this->items = $this->carService->getCar();
    }

    public function render()
    {
        return view('livewire.reservations.items-car');
    }
}
