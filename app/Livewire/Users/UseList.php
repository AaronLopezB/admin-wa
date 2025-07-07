<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\Log;

#[Lazy]
class UseList extends Component
{

    public $password, $password_confirmation, $user_id;
    public $authPassword;

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
}
