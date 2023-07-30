<?php

namespace Pietrantonio\NovaMailManager\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TemplateNotification extends Notification
{
    use Queueable, HasNotificationEmailTemplate;

    public function __construct()
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return $this->sendWithTemplate();
    }
}
