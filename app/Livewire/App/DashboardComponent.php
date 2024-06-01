<?php

namespace App\Livewire\App;

use App\Models\User;
use Livewire\Component;

class DashboardComponent extends Component
{
    public function render()
    {
        $users = User::all();

        return view('livewire.app.dashboard-component', compact('users'));
    }
}
