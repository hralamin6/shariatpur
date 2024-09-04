<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class DeleteNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $model;
    protected $user;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $model, $type)
    {
//        dd($model);
        $this->user = $user;
        $this->model = $model;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        try {
            return ['database'];
        } catch (\Exception $e) {
            \Log::error('Notification Error: ' . $e->getMessage());
            throw $e;
        }
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

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {

        return [
            'message' => 'A ' . class_basename($this->model) . ' was '. $this->type .' by ' . $this->user->name,
            'model' => $this->model->toArray(),
            'type' => $this->type,
            'by' => $this->user->name,

        ];
    }
}
