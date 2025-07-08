<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;
use Spatie\Permission\Models\Role;
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

    public function showPermissions($roleId)
    {
        $roles = Role::find($roleId);
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
            'role' => 'required|exists:roles,id',
            'location' => 'required|string',
            'key' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);
        dd($this->name, $this->email, $this->password, $this->role, $this->location, $this->key, $this->status);

        dd('event create');
    }
}
