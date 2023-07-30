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

Use **HasEmailTemplate** trait in your own Mailable, like this:

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
        $this->variable1 =
            'http://test.test?search=test';
        $this->variable2 = [
            1,
            2,
            3,
        ];

        // set variables to be passed to template
        $this->variables = [
            'variable1' => $this->variable1,
            'variable2' => implode(', ', $this->variable2),
            'customParameter' => $customParameter,
        ];

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