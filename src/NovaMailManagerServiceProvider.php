<?php

namespace Pietrantonio\NovaMailManager;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Nova;
use Pietrantonio\NovaMailManager\Resources\EmailTemplate;

class NovaMailManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-mail-manager');

        $this->publishes([
            __DIR__.'/../config/nova_mail_manager.php' => config_path('nova_mail_manager.php'),
            __DIR__.'/../resources/views' => resource_path('views/vendor/nova-mail-manager'),
        ]);

        Nova::resources([
            EmailTemplate::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/nova_mail_manager.php';
        $this->mergeConfigFrom($configPath, 'nova_mail_manager');
    }
}
