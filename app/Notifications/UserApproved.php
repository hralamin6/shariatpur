<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class UserApproved extends Notification
{
//    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $userName;
    public $body;
    public function __construct($userName, $body)
    {
        $this->userName = $userName;
        $this->body = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
//    public function via(object $notifiable): array
//    {
//        return ['mail'];
//    }
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }
    /**
     * Get the mail representation of the notification.
     */
//    public function toMail(object $notifiable): MailMessage
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage())
            ->title('New message from: ' . $this->userName)
            ->icon(getUserProfileImage(auth()->user()))
            ->body($this->body)
            ->action('View account', 'view_account')
            ->options(['TTL' => 1000])
            ->data([
                'url' => 'https://example.com/your-page' // Add the URL you want to redirect to
            ]);
        // ->badge()
        // ->dir()
        // ->image()
        // ->lang()
        // ->renotify()
        // ->requireInteraction()
        // ->tag()
        // ->vibrate()
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
//    public function toArray(object $notifiable): array
//    {
//        return [
//            //
//        ];
//    }
}
