<?php

namespace App\Livewire\App;

use Livewire\Component;

class NotificationComponent extends Component
{
    public $page;
    public function sendNotification()
    {
        $pusherBeams = app('PusherBeams');

        $pusherBeams->sendNotification(
            ['notify'], // Array of interests
            [
                "title" => "Hello!",
                "body" => "You have a new notification."
            ]
        );

        session()->flash('message', 'Notification sent successfully.');
    }

    public function mount($slug=null)
    {
        $this->page = \App\Models\Page::where('slug', $slug)->firstOrFail();

    }

    public function render()
    {
        return view('livewire.app.notification-component');
    }
}
