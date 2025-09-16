<?php

namespace App\Livewire\Web;

use App\Models\Notice;
use App\Models\Blog;
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

        $latestBlogs = Blog::with('blogCategory','user')
            ->where('status','active')
            ->latest()
            ->limit(8)
            ->get();

        return view('livewire.web.home-component', compact('headlines','latestBlogs'))
            ->layout('components.layouts.web');
    }
}
