<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserBaptizedNotification extends Notification
{
    use Queueable;
    protected $baptizedUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $baptizedUser)
    {
        $this->baptizedUser = $baptizedUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Congratulations on Your Baptism!')
                    ->line('Dear ' . $this->baptizedUser->name . ',')
                    ->line('We are pleased to inform you that you have been marked as baptized in our records.')
                    ->line('This is a significant milestone in your spiritual journey.')
                    ->line('If you have any questions, please don\'t hesitate to contact us.')
                    ->line('God bless you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'You have been marked as baptized. 
            You can now access your Baptism certificate and Recommandation letter',
            'baptism_date' => now()->toDateString(),
        ];
    }
}
