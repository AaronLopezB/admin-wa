<?php

namespace App\Livewire\ShoppingCar;

use Livewire\Component;
use App\Services\CarService;
use Livewire\Attributes\Lazy;

#[Lazy]
class Car extends Component
{
    protected $listeners = ['refreshShoppingCar' => '$refresh'];

    protected $carService;
    public $items = [];

    public function placeholder()
    {
        return <<<'HTML'
            <div class="row gy-3 p-15">
                <div class="col-sm-12 ">
                            <div class="placeholder-body">
                            <div class="placeholder-start">
                                <div class="square"></div>
                            </div>
                            <div class="placeholder-end">
                                <div class="placeholder-line placeholder-h-17 w-25 mb-2"></div>
                                <div class="placeholder-line"></div>
                                <div class="placeholder-line placeholder-h-8 w-50"></div>
                                <div class="placeholder-line w-75"></div>
                            </div>
                            </div>
                </div>
            </div>
        HTML;
    }

    public function boot(CarService $carService)
    {
        $this->carService = $carService;
        $this->items = $this->carService->getCar();
    }

    public function render()
    {
        return view('livewire.shopping-car.car');
    }
}
