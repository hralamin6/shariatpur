<?php

namespace App\Notifications;

    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;
    use Illuminate\Queue\SerializesModels;
    use NotificationChannels\WebPush\WebPushChannel;
    use NotificationChannels\WebPush\WebPushMessage;

class UserApproved extends Notification
{
    use Queueable, SerializesModels;

    /**
     * Create a new notification instance.
     */
    public $link;
    public $model;
    public $user;
    public $message;
    public function __construct($user, $model, $message, $link)
    {
//        dd($model);
        $this->user = $user;
        $this-> model = $model;
        $this-> message = $message;
        $this->link = $link;
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
        try {
            return [WebPushChannel::class, 'database'];
        } catch (\Exception $e) {
            \Log::error('Notification Error: ' . $e->getMessage());
            throw $e;
        }    }
    /**
     * Get the mail representation of the notification.
     */
        public function toMail($notifiable)
        {
            return (new MailMessage)
                ->subject('Account Approved')
                ->line("Your account has been approved by {$this->user->name}.")
                ->action('View Details', $this->link)
                ->line('Thank you for using our application!');
        }    public function toWebPush($notifiable, $notification)
    {
//        $user = User::find($this->user);
        return (new WebPushMessage())
            ->title('New message from: ' . $this->user->name)
//            ->icon('https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name))
            ->icon(getUserProfileImage($this->user))
            ->body($this->message)
            ->badge($this->link)
            ->action('View account', 'view_account')
            ->options(['TTL' => 1000])
            ->data([
                'url' => 'https://example.com/your-page' // Add the URL you want to redirect to
            ])->vibrate([200, 100, 200]);
    }
        public function toArray(object $notifiable): array
        {

            return [
                'message' => $this->message,
                'model' => $this->model->toArray(),
                'type' => 'edited',
                'link' => $this->link,
                'className' => class_basename($this->model),
                'changedByName' => $this->user->name,
                'changedById' => $this->user->id,
                'changedByRole' => $this->user->role->name,

            ];
        }

    }
