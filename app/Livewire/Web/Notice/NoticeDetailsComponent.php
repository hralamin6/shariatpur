<?php

namespace App\Livewire\Web\Notice;

use App\Models\Notice;
use Livewire\Component;

class NoticeDetailsComponent extends Component
{
    public $notice;
    public function mount($id)
    {
        $this->notice = Notice::findOrFail($id);
//        dd($this->notice);
    }
    public function render()
    {
        return view('livewire.web.notice.notice-details-component')->layout('components.layouts.web');
    }
}
