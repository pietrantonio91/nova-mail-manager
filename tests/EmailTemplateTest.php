<?php

namespace Pietrantonio\NovaMailManager\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Fields\Email;
use Pietrantonio\NovaMailManager\Mail\TestEmail;
use Pietrantonio\NovaMailManager\Models\EmailTemplate;
use Pietrantonio\NovaMailManager\Mail\CustomMailable;
use Tests\TestCase;

class EmailTemplateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_email_template_creation(): void
    {
        $this->createEmailTemplate();

        $emailTemplate = EmailTemplate::first();

        $this->assertEquals('Test email template', $emailTemplate->name);
        $this->assertEquals('test-email-template', $emailTemplate->slug);
        $this->assertEquals('Test email template subject', $emailTemplate->subject);
        $this->assertEquals('Test email template body', $emailTemplate->body);
    }

    public function test_email_template_update(): void
    {
        $emailTemplate = $this->createEmailTemplate();

        $emailTemplate->update([
            'name' => 'Updated email template',
            'slug' => 'updated-email-template',
            'subject' => 'Updated email template subject',
            'body' => 'Updated email template body',
        ]);

        $this->assertEquals('Updated email template', $emailTemplate->name);
        $this->assertEquals('updated-email-template', $emailTemplate->slug);
        $this->assertEquals('Updated email template subject', $emailTemplate->subject);
        $this->assertEquals('Updated email template body', $emailTemplate->body);
    }

    public function test_email_template_deletion(): void
    {
        $emailTemplate = $this->createEmailTemplate();

        $emailTemplate->delete();

        $this->assertDatabaseCount('email_templates', 0);
    }

    public function test_email_send_test_template()
    {
        $emailTemplate = $this->createEmailTemplate();

        Mail::fake();

        $emailTemplate->sendTestEmail('test@test.com');

        Mail::assertSent(TestEmail::class);
    }

    public function test_email_send_custom_mailable()
    {
        $emailTemplate = $this->createEmailTemplate();

        Mail::fake();

        Mail::to('test@test.com')
            ->send(new CustomMailable($emailTemplate));

        Mail::assertSent(CustomMailable::class);
    }

    private function createEmailTemplate(): EmailTemplate
    {
        return EmailTemplate::factory()->create([
            'name' => 'Test email template',
            'slug' => 'test-email-template',
            'subject' => 'Test email template subject',
            'body' => 'Test email template body',
        ]);
    }
}
