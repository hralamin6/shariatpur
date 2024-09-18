<?php

namespace App\Livewire\Web;

use Barryvdh\Debugbar\Facades\Debugbar;
use Livewire\Component;

class HomeComponent extends Component
{

    public function render()
    {
        return view('livewire.web.home-component')->layout('components.layouts.web');
    }
}
