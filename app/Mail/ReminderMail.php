<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Event $event)
    {
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Reminder',
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.reminder',
        );
    }

    public function attachments()
    {
        return [];
    }
}
