<?php

namespace App\Livewire\App;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class NotificationComponent extends Component
{
    use LivewireAlert;

    public $notifications;
    public $filter = 'all'; // default filter is 'all'

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if ($this->filter == 'unread') {
            $this->notifications = auth()->user()->role->unreadNotifications;
        } elseif ($this->filter == 'read') {
            $this->notifications = auth()->user()->role->readNotifications;
        } else {
            $this->notifications = auth()->user()->role->notifications;
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->role->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();  // Reload notifications after marking as read
            $this->alert('success', __('Data was mark as read successfully'));

        }
    }

    public function deleteNotification($notificationId)
    {
        $notification = auth()->user()->role->notifications()->find($notificationId);

        if ($notification) {
            $notification->delete();
            $this->loadNotifications();  // Reload notifications after deletion
            $this->alert('success', __('Data deleted successfully'));

        }
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadNotifications();  // Reload notifications with the new filter
    }

    public function render()
    {
        return view('livewire.app.notification-component');
    }
}
