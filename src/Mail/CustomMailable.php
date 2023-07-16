<?php

namespace Pietrantonio\NovaMailManager\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Pietrantonio\NovaMailManager\Models\EmailTemplate;

class CustomMailable extends Mailable {
    public function __construct(protected EmailTemplate $emailTemplate)
    {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailTemplate->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'nova-mail-manager::mail',
            with: [
                'emailTemplate' => $this->emailTemplate,
            ],
        );
    }
}
