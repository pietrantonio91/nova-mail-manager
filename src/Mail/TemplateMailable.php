<?php

namespace Pietrantonio\NovaMailManager\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Pietrantonio\NovaMailManager\Models\EmailTemplate;

class TemplateMailable extends Mailable {
    use HasEmailTemplate;

    public function __construct()
    {}
}
