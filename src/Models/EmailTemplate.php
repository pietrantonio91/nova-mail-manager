<?php

namespace Pietrantonio\NovaMailManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $table = 'email_templates';

    protected $fillable = [
        'name',
        'slug',
        'subject',
        'body',
        'from_name',
        'from_email',
        'cc',
        'bcc',
        'reply_to',
        'attachments',
        'variables',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'variables' => 'array',
        'attachments' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('nova_mail_manager.table_name');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function sendTestEmail($to)
    {
        $email = new \Pietrantonio\NovaMailManager\Mail\TestEmail($this);
        $email->to($to);
        return Mail::send($email);
    }

    protected static function newFactory()
    {
        return \Pietrantonio\NovaMailManager\Factories\EmailTemplateFactory::new();
    }
}
