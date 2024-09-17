<?php

namespace App\Livewire\Web;

use App\Models\Page;
use Livewire\Component;

class PageComponent extends Component
{   public  $page;

    public function mount($slug)
    {
        $this->page = Page::where('slug', $slug)->firstOrFail();
    }
    public function render()
    {
        return view('livewire.web.page-component')->layout('components.layouts.web');
    }
}
