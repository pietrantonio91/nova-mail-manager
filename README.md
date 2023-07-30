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
        $this->setTemplate('test-with-variables');
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

        ...
    }
}

```