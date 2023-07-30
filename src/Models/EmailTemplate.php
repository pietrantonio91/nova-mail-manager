<?php

namespace Pietrantonio\NovaMailManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class EmailTemplate extends Model
{
    use HasFactory;

    public $variables = [];

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

    public function sendTestEmail(string $to, array $variables = [])
    {
        $email = new \Pietrantonio\NovaMailManager\Mail\TemplateMailable();
        $email->setTemplate($this);
        $email->variables = $variables;
        $email->to($to);
        return Mail::send($email);
    }

    public function getFormattedBody(array $variables = [])
    {
        return $this->getFormattedText($this->body, $variables);
    }

    public function getFormattedSubject(array $variables = [])
    {
        return $this->getFormattedText($this->subject, $variables);
    }

    public function setVariables(array $variables)
    {
        $this->variables = $variables;
        return $this;
    }

    public function getVariables()
    {
        return $this->getVariablesFromText($this->body);
    }

    protected static function newFactory()
    {
        return \Pietrantonio\NovaMailManager\Factories\EmailTemplateFactory::new();
    }

    private function getVariablesFromText(string $text)
    {
        preg_match_all('/{{\s*\$(.*?)\s*}}/', $text, $variables);
        $variables = $variables[1];
        // trim variable names
        $variables = array_map('trim', $variables);
        // remove duplicates
        $variables = array_unique($variables);
        return array_values($variables);
    }

    private function getFormattedText(string $text, array $variables = [])
    {
        $variables = $variables ?: $this->variables;
        foreach ($variables as $variable => $value) {
            // replace variable removing the {{ }} and spaces from the variable name
            $text = $this->replaceVariable($text, $variable, $value);
        }
        return $text;
    }

    private function replaceVariable(string $text, string $variable, string $value)
    {
        return preg_replace(
            ['/{{\s*\$' . $variable . '\s*}}/'],
            $value,
            $text
        );
    }
}
