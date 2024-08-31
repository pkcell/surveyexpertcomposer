<?php

namespace Greenbit\SurveyExpert\Facades;

use Illuminate\Support\Facades\Facade;

class SurveyProvider extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'surveyprovider';
    }
}
