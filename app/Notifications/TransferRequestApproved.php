<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TransferRequest;

class TransferRequestApproved extends Notification
{
    use Queueable;

    protected $transferRequest;

    public function __construct(TransferRequest $transferRequest)
    {
        $this->transferRequest = $transferRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your transfer request has been approved.')
                    ->line('You have been transferred to ' . $this->transferRequest->toChurch->name . '.')
                    ->action('View Transfer Request', url('/transfer-requests/' . $this->transferRequest->id))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Your transfer request to ' . $this->transferRequest->toChurch->name . ' has been approved.',
            'transfer_request_id' => $this->transferRequest->id,
        ];
    }
}