<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

#[Lazy]
class Profile extends Component
{
    public User $user;
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
        // dd($this->user);
        $roles = Role::all();
        return view('livewire.users.profile', ['roles' => $roles]);
    }

    public function updateUser($id)
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
            $user = User::where('status', 'active')->find($id);
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

    #[On('disableAccountUser')]
    public function disable($id)
    {
        // dd($id);
        $this->resetValidation();
        try {
            $this->validate([
                'customerPasword' => 'required|string|min:8|current_password',
            ]);

            // dd(auth()->user()->hasRole('Admin'));
            if (!auth()->user()->hasRole('Admin')) {
                throw new \Exception("No puedes desactivar tu cuenta ", 1);
            }

            $user = User::where('status', 'active')->find($id);
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

    public function logoutUserSession($id)
    {
        if (config('session.driver') == 'database') {
            DB::table('session')
                ->where('user_id', $id)
                ->delete();
        }
        // Invalida el token de remember
        $user = User::find($id);
        $user->remember_token = null; // Invalida los tokens "remember me"
        $user->save();
    }

    #[On('activeAccountUser')]
    public function active($id)
    {
        try {
            $user = User::where('status', 'deactivate')->find($id);
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
}
