<?php

namespace Pietrantonio\NovaMailManager\Mail;

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
            subject: $this->emailTemplate->getFormattedSubject($this->variables),
        );
    }

    public function content(): Content
    {
        // If the email template is a string, we assume it's a slug, so we set the template by slug
        if ($this->emailTemplate && is_string($this->emailTemplate)) {
            $this->setTemplate($this->emailTemplate);
        }

        return new Content(
            view: 'nova-mail-manager::base',
        );
    }

    /**
     * Set the email template by slug or EmailTemplate model
     *
     * @param string|EmailTemplate $emailTemplate
     * @return self
     */
    public function setTemplate(string|EmailTemplate $emailTemplate): self
    {
        if (is_string($emailTemplate)) {
            $this->emailTemplate = EmailTemplate::where('slug', $emailTemplate)->firstOrFail();
        } else {
            $this->emailTemplate = $emailTemplate;
        }
        return $this;
    }

    /**
     * Set the variables for the email template
     *
     * @param array $variables
     * @return self
     */
    public function setVariables(array $variables): self
    {
        $this->emailTemplate->setVariables($variables);
        return $this;
    }
}
