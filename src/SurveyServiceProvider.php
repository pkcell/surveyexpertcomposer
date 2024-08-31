<?php

namespace Greenbit\SurveyExpert;

use Illuminate\Support\ServiceProvider;
use Greenbit\SurveyExpert\Facades\SurveyBuilder;
use Greenbit\SurveyExpert\Facades\SurveyProvider;

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
