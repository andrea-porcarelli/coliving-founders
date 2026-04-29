<?php

namespace App\Mail;

use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public FormSubmission $submission)
    {
    }

    public function envelope(): Envelope
    {
        $labels = [
            'partner' => 'Become a Partner application',
            'workation' => 'Workation Plan request',
            'contact' => 'Contact message',
        ];
        $label = $labels[$this->submission->type] ?? 'Form submission';
        $from = data_get($this->submission->payload, 'email');

        return new Envelope(
            subject: "[COFO] {$label}",
            replyTo: $from ? [$from] : [],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.submission-received');
    }
}
