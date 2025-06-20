<?php

namespace App\Livewire\Reservations;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use App\Models\ReservationType;
use Livewire\Attributes\Validate;

#[Lazy]
class Order extends Component
{

    #[Validate('required', message: 'El nombre es requerido')]
    // #[Validate('string', message: 'El n')]
    public $name;

    #[Validate('required', message: 'Los apellidos son requerido')]
    public $last_name;

    #[Validate('required', message: 'El teléfono es obligatorio.')]
    // #[Validate('regex:/^\+?[0-9]{9,15}$/', message: 'El teléfono debe contener entre 9 y 15 dígitos y puede incluir un prefijo de país opcional.')]
    public $phone;

    #[Validate('required', message: 'El correo electrónico debe ser una dirección válida.')]
    #[Validate('email', message: 'El correo electrónico debe ser una dirección válida.')]
    public $email;

    #[Validate('required|not_in:0', message: 'Como se entero es obligatorio')]
    public $platform;

    public $type;

    public $infoCustomer;

    public $pay = 1;
    public $dataCuestomer;
    public $payment_token;

    public function boot()
    {
        $this->infoCustomer = session()->has('cli') ? true : false;
        $this->dataCuestomer = session()->get('cli');
    }

    public function placeholder()
    {
        return view('livewire.placeholder.load-component');
    }

    public function render()
    {
        $this->type = ReservationType::all();
        return view('livewire.reservations.order');
    }

    public function infoCustomer()
    {
        // dd("hola");
        $this->validate();

        session()->put('cli', [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'platfor' => $this->platform
        ]);

        $this->dispatch('notify', msj: 'Se registro correctamente el usuario', type: 'success', method: 'infoCustomer');
    }

    public function payment()
    {
        if ($this->pay === "0") {
            dd($this->pay);
            # code...
        }
        // dd($this->pay);
        if ($this->pay == "1") {
            $this->dispatch('processPaymentMethod');
        }
    }
}
