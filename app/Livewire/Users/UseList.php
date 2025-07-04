<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class UseList extends Component
{

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
}
