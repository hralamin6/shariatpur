<?php

namespace App\Livewire\App;

use Livewire\Component;

class NotificationComponent extends Component
{
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

    public function render()
    {
        return view('livewire.app.notification-component');
    }
}
