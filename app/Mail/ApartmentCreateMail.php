<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApartmentCreateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $apartment;

    /**
     * Create a new message instance.
     */
    public function __construct($apartment)
    {
        // Automatic Call
        $this->apartment = $apartment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Mail Subject
        return new Envelope(
            subject: 'Apartment Create Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Body Message
        return new Content(
            view: 'emails.apartment_created',
            with: [
                'apartment' => $this->apartment,
            ],
        );
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
