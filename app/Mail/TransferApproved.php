<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\TransferRequest;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransferApproved extends Mailable
{
    use Queueable, SerializesModels;
    public $transferRequest;

    /**
     * Create a new message instance.
     */
    public function __construct(TransferRequest $transferRequest)
    {
        $this->transferRequest = $transferRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Transfer Request Has Been Approved',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtmlContent(),
        );
    }

    /**
     * Build the HTML content for the email.
     */
    private function buildHtmlContent(): string
    {
        $christian = $this->transferRequest->christian;
        $toChurch = $this->transferRequest->toChurch;
        $approvedBy = $this->transferRequest->approvedBy;

        return <<<HTML
        <h1>Your Transfer Request Has Been Approved</h1>

        <p>Dear {$christian->firstname},</p>

        <p>We are pleased to inform you that your transfer request has been approved. You are now officially transferred to {$toChurch->name}.</p>

        <p>Welcome to our ministry! We look forward to your participation and contribution.</p>

        <p>Best regards,<br>
        Pastor. {$approvedBy->firstname} {$approvedBy->lastname}</p>
        HTML;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}