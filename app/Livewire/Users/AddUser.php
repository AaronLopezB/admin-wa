<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

#[Lazy]
class AddUser extends Component
{

    public $name, $email, $password, $password_confirmation, $location, $key, $status = true;
    public $role, $permission;

    public function placeholder()
    {
        return view('livewire.placeholder.load-component');
    }

    public function render()
    {
        $roles = Role::all();
        // $permissions = Permission::all();
        // dd($roles, $permissions);
        return view('livewire.users.add-user', [
            'roles' => $roles,
            // 'permissions' => $permissions
        ]);
    }

    public function showPermissions($roleName)
    {
        $roles = Role::where('name', $roleName)->first();
        // dd($roles);
        if ($roles) {
            $this->dispatch('showPermissions', permissions: $roles->permissions);
        }
    }

    public function createUser()
    {
        $this->resetValidation();
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'location' => 'required|string',
            'key' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        try {
            //code...
            $user = DB::transaction(function () {

                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt($this->password),
                    'location' => $this->location,
                    'key' => $this->key,
                    'status' => ($this->status == true) ? 'active' : 'deactivate',
                ]);
                $user->assignRole($this->role);
                return $user;
            });
            $this->reset(
                'name',
                'email',
                'password',
                'password_confirmation',
                'role',
                'location',
                'key',
                'status'
            );
            $this->dispatch('alert', msj: "Usuario {$user->name} creado correctamente", type: "success", method: "createUser");
        } catch (\Exception $e) {
            // throw $th;
            Log::error("message: " . $e->getMessage() . " - Line: " . $e->getLine());
            $this->dispatch(
                'alert',
                type: 'error',
                msj: 'Error al crear la cuenta: ' . $e->getMessage(),
                method: 'createUser'
            );
        }
    }
}
