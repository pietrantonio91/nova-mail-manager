<?php 

namespace Pietrantonio\NovaMailManager\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Pietrantonio\NovaMailManager\Models\EmailTemplate;

trait HasNotificationEmailTemplate
{
    public string|EmailTemplate $emailTemplate = '';
    public array $variables = [];

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return $this->sendWithTemplate();
    }

    /**
     * Send notification with chosen email template and set variables.
     */
    public function sendWithTemplate()
    {
        return (new MailMessage)
            ->subject($this->emailTemplate->getFormattedSubject($this->variables))
            ->view('nova-mail-manager::base', [
                'emailTemplate' => $this->emailTemplate,
                'variables' => $this->variables,
            ]);
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