<?php

namespace Pietrantonio\NovaMailManager\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Pietrantonio\NovaMailManager\Models\EmailTemplate;

trait HasEmailTemplate
{
    public string|EmailTemplate $emailTemplate = '';
    public array $variables = [];

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailTemplate->subject,
        );
    }

    public function content(): Content
    {
        if ($this->emailTemplate && is_string($this->emailTemplate)) {
            $this->setTemplate($this->emailTemplate);
        }

        return new Content(
            view: 'nova-mail-manager::base',
        );
    }

    public function setTemplate(string|EmailTemplate $emailTemplate)
    {
        if (is_string($emailTemplate)) {
            $this->emailTemplate = EmailTemplate::where('slug', $emailTemplate)->firstOrFail();
        } else {
            $this->emailTemplate = $emailTemplate;
        }
        return $this;
    }

    private function setVariables(array $variables)
    {
        $this->emailTemplate->setVariables($variables);
        return $this;
    }
}
