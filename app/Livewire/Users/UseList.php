<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

#[Lazy]
class UseList extends Component
{

    public $password, $password_confirmation, $user_id, $key, $location;
    public $authPassword;

    public $role;

    public function placeholder()
    {
        return view('livewire.placeholder.load-component');
    }

    public function render()
    {
        $users = User::orderBy('id', 'DESC')
            ->paginate(10, pageName: 'pageUsers');
        return view('livewire.users.use-list', [
            'user' => $users
        ]);
    }

    public function editPassword()
    {
        $this->resetValidation();
        $this->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        $this->dispatch('validUser');
        // dd($this->user_id, $this->password, $this->current_password);
    }

    #[On('updatePassword')]
    public function password()
    {
        try {

            $this->validate([
                'authPassword' => 'required|string|min:8|current_password',
            ]);
            // dd($this->user_id, $this->password, $this->current_password);
            $user = User::find($this->user_id);
            $user->password = bcrypt($this->password);

            if ($user->save()) {
                $this->dispatch(
                    'alert',
                    type: 'success',
                    msj: "Contraseña actualizada correctamente.",
                    method: 'updatePassword'
                );
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // dd($e);
            // Log::error("message: " . $e->errors() . " - Line: " . $e->getLine());
            $this->dispatch(
                'alert',
                type: 'error',
                msj: "La contraseña actual es incorrecta.",
                method: 'updatePasswordError'
            );
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . " - Line: " . $e->getLine());
            $this->dispatch(
                'alert',
                type: 'error',
                msj: 'Error al actualizar el perfil: ' . $e->getMessage(),
                method: 'updatePasswordError'
            );
        }
    }

    #[On('update-role')]
    public function roles($user_id)
    {
        $roles = Role::all();
        // dd($roles);
        $this->dispatch('showRoles', roles: $roles, user_id: $user_id);
    }

    public function updateRole()
    {
        $this->resetValidation();
        $this->validate([
            'role' => 'required|exists:roles,name',
        ]);
        try {
            //code...
            $user = User::find($this->user_id);
            if (!$user) {
                throw new \Exception("Usuario no encontrado", 1);
            }
            $user->syncRoles([$this->role]);
            $this->reset([
                'user_id',
                'role'
            ]);
            $this->dispatch('alert', msj: "Rol actualizado correctamente", type: "success", method: 'updateRole');
            // $user->dd($this->user_id, $this->role);
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . " - Line: " . $e->getLine());
            $this->dispatch(
                'alert',
                type: 'error',
                msj: 'Error al actualizar el rol: ' . $e->getMessage(),
                method: 'updateRole'
            );
        }
    }

    #[On('updateStatus')]
    public function update_status($user_id)
    {
        $user = User::find($user_id);
        dd($user);
        if (!$user) {
            $this->dispatch(
                'alert',
                type: 'error',
                msj: 'Usuario no encontrado.',
                method: 'updateStatus'
            );
            return;
        }
        $status = ($user->status != 'deactivate') ? 'deactivate' : 'active';
        $user->status = $status;
        if ($user->save()) {
            $this->dispatch(
                'alert',
                type: 'success',
                msj: 'Usuario actualizado correctamente.',
                method: 'updateStatus'
            );
        } else {
            $this->dispatch(
                'alert',
                type: 'error',
                msj: 'Error al actualizar es estatus.',
                method: 'updateStatus'
            );
        }
    }
}
