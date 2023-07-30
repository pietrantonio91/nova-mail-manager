# Installation

## Install package

```
composer require pietrantonio/nova-mail-manager
```

## Database

```
php artisan migrate
```

## File manager

```
php artisan vendor:publish --tag=lfm_config
php artisan vendor:publish --tag=lfm_public
```

# Usage

## Mailable

Use **HasEmailTemplate** trait in your own Mailable, and then set template you want to use by method **setTemplate**, like this:

```
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Pietrantonio\NovaMailManager\Mail\HasEmailTemplate;

class MyCustomMailable extends Mailable
{
    // use HasEmailTemplate trait
    use Queueable, SerializesModels, HasEmailTemplate;

    public string $variable1;
    public array $variable2;

    public function __construct(public string $customParameter) 
    {
        ...

        // set template by slug
        $this->setTemplate('my-email-template-slug');
    }
}

```

Use your Mailable like this:

```
Mail::to('test@test.test')
    ->send(
        new MyCustomMailable()
    );
```

### Mailable - Variables

If you want to use variables in your Mailable, add a property **variables** to your Mailable Class, like this:

```
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Pietrantonio\NovaMailManager\Mail\HasEmailTemplate;

class MyCustomMailable extends Mailable
{
    // use HasEmailTemplate trait
    use Queueable, SerializesModels, HasEmailTemplate;

    public function __construct(public string $customParameter) 
    {
        $variable1 = 'http://test.test?search=test';
        $variable2 = [
            1,
            2,
            3,
        ];

        // set variables to be passed to template
        $this->variables = [
            'variable1' => $variable1,
            'variable2' => implode(', ', $variable2),
            'customParameter' => $customParameter,
        ];

        // or use the method setVariables
        $this->setVariables([
            'variable1' => $variable1,
            'variable2' => implode(', ', $variable2),
            'customParameter' => $customParameter,
        ]);

        ...
    }
}

```

## Notifications

Use **HasNotificationEmailTemplate** trait in your own Notification class, and then set template you want to use by method **setTemplate**,
then use **$this->sendWithTemplate()** method to send the notification email with custom template, subject and body. 
Example:

```
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Pietrantonio\NovaMailManager\Notifications\HasNotificationEmailTemplate;

class MyCustomNotification extends Notification
{
    // use HasNotificationEmailTemplate trait
    use Queueable, HasNotificationEmailTemplate;

    ...

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // set custom email by slug
        $this->setTemplate('my-email-template-slug');

        // send notification by trait's method sendWithTemplate
        return $this->sendWithTemplate();
    }

    ...
}

```

### Notification - Variables

If you want to use variables in your Notification, add a property **variables** to your Notification class or use he method **setVariables**.
Example:

```
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Pietrantonio\NovaMailManager\Notifications\HasNotificationEmailTemplate;

class MyCustomNotification extends Notification
{
    // use HasNotificationEmailTemplate trait
    use Queueable, HasNotificationEmailTemplate;

    ...

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        ...

        $variable1 = 'http://test.test?search=test';
        $variable2 = [
            1,
            2,
            3,
        ];

        // set variables to be passed to template
        $this->variables = [
            'variable1' => $variable1,
            'variable2' => implode(', ', $variable2),
            'customParameter' => $customParameter,
        ];

        // or use the method setVariables
        $this->setVariables([
            'variable1' => $variable1,
            'variable2' => implode(', ', $variable2),
            'customParameter' => $customParameter,
        ]);

        // send notification by trait's method sendWithTemplate
        return $this->sendWithTemplate();
    }

    ...
}

```