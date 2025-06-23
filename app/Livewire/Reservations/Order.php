<?php

namespace App\Livewire\Reservations;

use Exception;
use Carbon\Carbon;
use App\Models\Code;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\CarService;
use Livewire\Attributes\Lazy;
use App\Models\ReservationType;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;

#[Lazy]
class Order extends Component
{
    protected $listeners = ['refreshPayment' => '$refresh'];

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

    protected $carService;
    public $items;

    // #[Validate('required|string|max:255')]
    public $coupon;

    public function boot(CarService $carService)
    {
        $this->carService = $carService;
        $this->items = $this->carService->getCar();
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

    public function formInfoCustomer()
    {
        try {
            $this->validate();
            session()->put('cli', [
                'name' => $this->name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'platfor' => $this->platform
            ]);

            $this->dispatch('notify', msj: 'Se registro correctamente el usuario', type: 'success', method: 'infoCustomer');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // dd($e->errors());
            $this->dispatch('notify', errors: $e->errors(), type: 'error', method: 'errorValidationFormCustomer');
        } catch (\Throwable $th) {
            $this->dispatch('notify', errors: 'Se produjo un error al guardar el usuario', type: 'error', method: 'errorInfoCustomer');
            // dd($th);
        }
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

    public function addCoupon()
    {
        // dd($this->coupon);
        $this->validate([
            'coupon' => 'required|string|max:255'
        ]);
        try {
            $code = Code::where('codigo', $this->coupon)->where('estatus', 1)->first();
            if (!$code) {
                throw new Exception("Cupon no encontrado o invalido", 1);
            }
            $validate = Carbon::parse($code->valides);
            if ($validate->isPast()) {
                throw new Exception("El cupón ha expirado.", 1);
            }
            $this->carService->addCode($code->id);

            $this->dispatch('notify', msj: 'Cupón no válido', response: 'error', method: 'applyCoupon');
        } catch (\Exception $e) {
            Log::error("Error al aplicar cupón: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
                // Registrar el error en los logs
            ]);

            $this->dispatch('notify', msj: 'Cupón no válido', response: 'error', method: 'applyCoupon');
        }
    }

    #[On('deleteCoupon')]
    public function delete($code)
    {
        // dd($code);
        try {
            $delete = $this->carService->deleteCouponS($code);
            dd($delete);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    #[On('paymentMethodCreated')]
    public function paymentOrder($paymentToken)
    {
        try {
            if (config('secret.active') != true) {
                $this->dispatch('notify', msj: 'Se ha producido un error al procesar el pago, inténtelo de nuevo más tarde.', method: 'deactivatedPayments', reply: 'warning');
                return;
            }
            $subtotal = $this->items->sum('total');
        } catch (\Throwable $th) {
            //throw $th;
        }
        dd($paymentToken);
    }
}
