<?php

namespace Greenbit\SurveyExpert\Builders;

class TranslatableBuilder
{
    protected $translations = [];

    public function setTranslations(array $translations)
    {
        $this->translations = $translations;
        return $this;
    }

    public function addTranslation($language, $value)
    {
        $this->translations[$language] = $value;
        return $this;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function getTranslation($language)
    {
        return $this->translations[$language] ?? null;
    }
}
