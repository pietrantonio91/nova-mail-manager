<?php

namespace Pietrantonio\NovaMailManager\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Pietrantonio\NovaMailManager\Models\EmailTemplate;
use Pietrantonio\NovaMailManager\Mail\TemplateMailable;
use Pietrantonio\NovaMailManager\Notifications\TemplateNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailTemplateTest extends TestCase
{
    use RefreshDatabase;

    private const TEMPLATE_SLUG = 'test-email-template';

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
        $this->assertEquals('Hello {{ $name }} this is an email for you', $emailTemplate->body);
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

        Mail::assertSent(TemplateMailable::class);
    }

    public function test_email_send_custom_mailable()
    {
        $emailTemplate = $this->createEmailTemplate();

        Mail::fake();

        $customMailable = new TemplateMailable();
        $customMailable->setTemplate($emailTemplate);

        Mail::to('test@test.com')
            ->send($customMailable);

        Mail::assertSent(TemplateMailable::class);
    }

    public function test_email_send_custom_mailable_by_slug()
    {
        $emailTemplate = $this->createEmailTemplate();

        Mail::fake();

        $customMailable = new TemplateMailable();
        $customMailable->setTemplate($emailTemplate->slug);

        Mail::to('test@test.com')
            ->send($customMailable);

        Mail::assertSent(TemplateMailable::class);
    }

    public function test_email_send_custom_mailable_by_slug_with_variables()
    {
        $emailTemplate = $this->createEmailTemplate();

        Mail::fake();

        $customMailable = new TemplateMailable();
        $customMailable->variables = [
            'name' => 'John Doe'
        ];
        $customMailable->setTemplate($emailTemplate->slug);

        $this->assertStringContainsString(
            "Hello {$customMailable->variables['name']} this is an email for you", 
            $customMailable->render()
        );
        
        Mail::to('test@test.com')
            ->send($customMailable);

        Mail::assertSent(TemplateMailable::class);
    }

    public function test_email_send_mailable_with_variable_in_subject()
    {
        $emailTemplate = $this->createEmailTemplate();
        $emailTemplate->update([
            'subject' => 'Hello {{ $name }} this is an email for you',
        ]);

        Mail::fake();

        $customMailable = new TemplateMailable();
        $customMailable->variables = [
            'name' => 'John Doe'
        ];
        $customMailable->setTemplate($emailTemplate->slug);

        $this->assertEquals(
            $customMailable->emailTemplate->getFormattedSubject($customMailable->variables),
            "Hello {$customMailable->variables['name']} this is an email for you"
        );

        Mail::to('test@test.com')
            ->send($customMailable);        

        Mail::assertSent(TemplateMailable::class);
    }

    public function test_email_send_notification()
    {
        $emailTemplate = $this->createEmailTemplate();
        $user = \App\Models\User::factory()->create();

        Notification::fake();

        $customNotification = new TemplateNotification();
        $customNotification->setTemplate($emailTemplate->slug);

        $this->assertEquals($emailTemplate->slug, $customNotification->emailTemplate->slug);
    
        $user->notify($customNotification);

        Notification::assertSentTo(
            [$user], TemplateNotification::class
        );
    }

    public function test_email_send_notification_with_variables()
    {
        $emailTemplate = $this->createEmailTemplate();
        $user = \App\Models\User::factory()->create();

        Notification::fake();

        $customNotification = new TemplateNotification();
        $customNotification->variables = [
            'name' => 'John Doe'
        ];
        $customNotification->setTemplate($emailTemplate->slug);

        $this->assertEquals($emailTemplate->slug, $customNotification->emailTemplate->slug);

        $this->assertStringContainsString(
            "Hello {$customNotification->variables['name']} this is an email for you", 
            $customNotification->emailTemplate->getFormattedBody($customNotification->variables)
        );
    
        $user->notify($customNotification);

        Notification::assertSentTo(
            [$user], TemplateNotification::class
        );
    }

    public function test_email_send_notification_with_variables_in_subject()
    {
        $emailTemplate = $this->createEmailTemplate();
        $emailTemplate->update([
            'subject' => 'Hello {{ $name }} this is an email for you',
        ]);
        $user = \App\Models\User::factory()->create();

        Notification::fake();

        $customNotification = new TemplateNotification();
        $customNotification->variables = [
            'name' => 'John Doe'
        ];
        $customNotification->setTemplate($emailTemplate->slug);

        $this->assertEquals($emailTemplate->slug, $customNotification->emailTemplate->slug);

        $this->assertEquals(
            $customNotification->emailTemplate->getFormattedSubject($customNotification->variables),
            "Hello {$customNotification->variables['name']} this is an email for you"
        );
    
        $user->notify($customNotification);

        Notification::assertSentTo(
            [$user], TemplateNotification::class
        );
    }

    private function createEmailTemplate(): EmailTemplate
    {
        return EmailTemplate::factory()->create([
            'name' => 'Test email template',
            'slug' => self::TEMPLATE_SLUG,
            'subject' => 'Test email template subject',
            'body' => 'Hello {{ $name }} this is an email for you',
        ]);
    }
}
