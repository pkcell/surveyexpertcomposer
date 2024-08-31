<?php

namespace App\Classes\SurveyExpert\Parts\Facades;

use Illuminate\Support\Facades\Facade;


class SurveyBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'surveybuilder';
    }
}
