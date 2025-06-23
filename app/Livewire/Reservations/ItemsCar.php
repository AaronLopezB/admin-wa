<?php

namespace App\Livewire\Reservations;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\CarService;
use Livewire\Attributes\Lazy;

#[Lazy]
class ItemsCar extends Component
{
    protected $listeners = ['refreshItemPayment' => '$refresh'];
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

    #[On('addDiscount')]
    public function discountVehicle($discount, $vehicle_id)
    {
        $addDiscount = $this->carService->addDiscountByItem($vehicle_id, $discount);
        $this->dispatch('refreshItemPayment');
        // dd($addDiscount, 'discount v');
    }
}
