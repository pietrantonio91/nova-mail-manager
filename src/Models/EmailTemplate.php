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

        // set table name from config so devs can change it
        $this->table = config('nova_mail_manager.table_name');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Send email using base template
     *
     * @param string $to
     * @param array $variables
     * @return \Illuminate\Mail\SentMessage|null
     */
    public function sendTestEmail(string $to, array $variables = [])
    {
        $email = new \Pietrantonio\NovaMailManager\Mail\TemplateMailable();
        $email->setTemplate($this);
        $email->variables = $variables;
        $email->to($to);
        return Mail::send($email);
    }

    /**
     * Get formatted body with variables
     *
     * @param array $variables
     * @return string
     */
    public function getFormattedBody(array $variables = []): string
    {
        return $this->getFormattedText($this->body, $variables);
    }

    /**
     * Get formatted subject with variables
     *
     * @param array $variables
     * @return string
     */
    public function getFormattedSubject(array $variables = []): string
    {
        return $this->getFormattedText($this->subject, $variables);
    }

    public function setVariables(array $variables)
    {
        $this->variables = $variables;
        return $this;
    }

    /**
     * Get variables from body and subject, merge and remove duplicates.
     *
     * @return array
     */
    public function getVariables(): array
    {
        $variablesFromBody = $this->getVariablesFromText($this->body);
        $variablesFromSubject = $this->getVariablesFromText($this->subject);
        $variables = array_merge($variablesFromBody, $variablesFromSubject);
        // remove duplicates
        $variables = array_unique($variables);
        return array_values($variables);
    }

    protected static function newFactory()
    {
        return \Pietrantonio\NovaMailManager\Factories\EmailTemplateFactory::new();
    }

    /**
     * Get variables from passed text, trim names and remove duplicates.
     *
     * @param string $text
     * @return array
     */
    private function getVariablesFromText(string $text): array
    {
        preg_match_all('/{{\s*\$(.*?)\s*}}/', $text, $variables);
        $variables = $variables[1];
        // trim variable names
        $variables = array_map('trim', $variables);
        // remove duplicates
        $variables = array_unique($variables);
        return array_values($variables);
    }

    /**
     * Replace variables in passed text with variables' values.
     *
     * @param string $text
     * @param array $variables
     * @return string
     */
    private function getFormattedText(string $text, array $variables = []): string
    {
        $variables = $variables ?: $this->variables;
        foreach ($variables as $variable => $value) {
            // replace variable removing the {{ }} and spaces from the variable name
            $text = $this->replaceVariable($text, $variable, $value);
        }
        return $text;
    }

    /**
     * Replace single variable in text with value.
     *
     * @param string $text
     * @param string $variable
     * @param string $value
     * @return string
     */
    private function replaceVariable(string $text, string $variable, string $value): string
    {
        return preg_replace(
            ['/{{\s*\$' . $variable . '\s*}}/'],
            $value,
            $text
        );
    }
}
