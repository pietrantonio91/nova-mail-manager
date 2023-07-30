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
            $email = $fields->email;
            unset($fields->email);
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
        $emailTemplate = $request->findModelQuery()->first();
        if (!$emailTemplate && $request->resources) {
            $emailTemplate = EmailTemplate::find($request->resources);
        }
        $variables = $emailTemplate?->getVariables() ?? [];

        $fields = [
            Text::make('Email')->rules(['required', 'email']),
        ];
        
        foreach ($variables as $variable) {
            $fields[] = Text::make($variable)->rules(['required']);
        }

        return $fields;
    }
}
