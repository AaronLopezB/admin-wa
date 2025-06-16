<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

#[Lazy]
class Profile extends Component
{
    protected $listeners = ['refreshProf' => '$refresh'];

    public $name,
        $email,
        $password,
        $password_confirmation,
        $role;

    public $customerPasword;

    public function placeholder()
    {
        return view('livewire.placeholder.load-component');
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.user.profile', [
            'roles' => $roles,
        ]);
    }


    public function updateProfile()
    {
        // dd($this->name, $this->email, $this->password, $this->role);
        $this->resetValidation();
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        try {
            //code...
            $user = User::where('status', 'active')->find(auth()->id());
            if (!$user) {
                throw new \Exception("No existe el usuario", 1);
            }
            $user->name = $this->name;
            $user->email = $this->email;
            if ($this->password) {
                $user->password = bcrypt($this->password);
            }
            $user->assignRole($this->role);
            // dd($user);
            $user->save();
            if ($user) {
                $this->reset(
                    'name',
                    'email',
                    'password',
                    'password_confirmation',
                    'role'
                );
                $this->dispatch(
                    'alert',
                    type: 'success',
                    msj: 'Perfil actualizado correctamente.',
                    method: 'updateUser'
                );
            }
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . " - Line: " . $e->getLine());
            $this->dispatch(
                'alert',
                type: 'error',
                msj: 'Error al actualizar el perfil: ' . $e->getMessage(),
                method: 'updateUser'
            );
        }
    }

    #[On('disableAccountU')]
    public function disableAccountCurrend()
    {
        // dd($this->customerPasword, 'customerPasword');
        $this->resetValidation();
        try {
            $this->validate([
                'customerPasword' => 'required|string|min:8|current_password',
            ]);

            // dd(auth()->user()->hasRole('Admin'));
            if (!auth()->user()->hasRole('Admin')) {
                throw new \Exception("No puedes desactivar tu cuenta ", 1);
            }

            $user = User::where('status', 'active')->find(auth()->id());
            if (!$user) {
                throw new \Exception("No existe el usuario", 1);
            }
            $user->status = 'deactivate';
            $user->save();
            // auth()->logout();
            $this->dispatch(
                'alert',
                type: 'success',
                msj: 'Cuenta desactivada correctamente.',
                method: 'disableAccountCurrend'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            // dd($e);
            // Log::error("message: " . $e->errors() . " - Line: " . $e->getLine());
            $this->dispatch(
                'alert',
                type: 'error',
                msj: "La contraseña actual es incorrecta.",
                method: 'disableAccountCurrendError'
            );
        } catch (\Exception $e) {
            // dd($e);
            Log::error("message: " . $e->getMessage() . " - Line: " . $e->getLine());
            $this->dispatch(
                'alert',
                type: 'error',
                msj: 'Error al desactivar la cuenta: ' . $e->getMessage(),
                method: 'disableAccountCurrendError'
            );
        }
    }

    #[On('activeAccountU')]
    public function activeAccountCurrend()
    {
        try {
            $user = User::where('status', 'deactivate')->find(auth()->id());
            if (!$user) {
                throw new \Exception("No existe el usuario", 1);
            }
            $user->status = 'active';
            $user->save();
            $this->dispatch(
                'alert',
                type: 'success',
                msj: 'Cuenta activada correctamente.',
                title: 'Cuenta activada',
                method: 'activeAccountCurrend'
            );
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . " - Line: " . $e->getLine());
            $this->dispatch(
                'alert',
                type: 'error',
                msj: 'Error al activar la cuenta: ' . $e->getMessage(),
                title: 'Error al activar la cuenta',
                method: 'activeAccountCurrend'
            );
        }
    }

    #[On('logoutUser')]
    public function logoutUser()
    {
        try {
            auth()->logout();

            session()->invalidate();

            session()->regenerateToken();
            session()->flash('alert', 'Sesión cerrada correctamente.');
            $this->redirect(route('login', absolute: false));
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . " - Line: " . $e->getLine());
            $this->dispatch(
                'alert',
                type: 'error',
                msj: 'Error al cerrar sesión: ' . $e->getMessage(),
                title: 'Error al cerrar sesión',
                method: 'logoutUser'
            );
        }
    }
}
