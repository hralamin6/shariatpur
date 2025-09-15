<?php

namespace App\Livewire\Web;

use App\Models\Notice;
use Barryvdh\Debugbar\Facades\Debugbar;
use Livewire\Component;

class HomeComponent extends Component
{

    public function render()
    {
    $headlines = Notice::
    orderByDesc('pinned')
    ->orderByDesc('created_at')
    ->limit(10)
    ->get(['id', 'title'])
    ->toArray();
//    dd($headlines);
        return view('livewire.web.home-component', compact('headlines'))
            ->layout('components.layouts.web');
    }
}
