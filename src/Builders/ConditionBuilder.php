<?php

namespace Greenbit\SurveyExpert\Builders;

class ConditionBuilder
{
    protected $field;
    protected $value;
    protected $operator;

    public function field($field)
    {
        $this->field = $field;
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function operator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    public function evaluate($answers)
    {
        if (!isset($answers[$this->field])) {
            return false;
        }

        $userValue = $answers[$this->field];

        return match ($this->operator) {
            '==' => $userValue == $this->value,
            '!=' => $userValue != $this->value,
            '>' => $userValue > $this->value,
            '<' => $userValue < $this->value,
            default => false,
        };
    }
}
