<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Lazy]
class AddUser extends Component
{

    public $name, $email, $password, $password_confirmation;
    public $role, $permissions;

    public function placeholder()
    {
        return view('livewire.placeholder.load-component');
    }

    public function render()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('livewire.users.add-user', [
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }
}
