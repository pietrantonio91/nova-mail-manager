<?php

namespace Pietrantonio\NovaMailManager\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Pietrantonio\NovaMailManager\Models\EmailTemplate;

class TestEmailTemplate extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            // get email from fields and remove it from fields
            $email = $fields->email;
            unset($fields->email);
            // send test email with email recipient and array of fields (variables)
            $model->sendTestEmail(
                $email,
                $fields->toArray()
            );
        }

        return Action::message('Test email sent!');
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        // get selected model from request
        $emailTemplate = $request->findModelQuery()->first();
        if (!$emailTemplate && $request->resources) {
            $emailTemplate = EmailTemplate::find($request->resources);
        }
        // get variables from body and subject
        $variables = $emailTemplate?->getVariables() ?? [];

        // create fields: the first one is the email to send the test to
        $fields = [
            Text::make('Email')->rules(['required', 'email']),
        ];
        
        // add variables fields
        foreach ($variables as $variable) {
            $fields[] = Text::make($variable)->rules(['required']);
        }

        return $fields;
    }
}
