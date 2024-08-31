<?php

namespace App\Classes\SurveyExpert\Parts;

use App\Classes\SurveyExpert\Parts\Builders\SurveyBuilder;
use App\Classes\SurveyExpert\Parts\Providers\SurveyProvider;
use Illuminate\Support\ServiceProvider;

class SurveyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Bind SurveyProvider
        $this->app->singleton('surveyprovider', function ($app) {
            return new SurveyProvider();
        });

        // Bind SurveyBuilder
        $this->app->singleton('surveybuilder', function ($app) {
            return new SurveyBuilder();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
