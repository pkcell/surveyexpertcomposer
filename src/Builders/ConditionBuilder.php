<?php

namespace Greenbit\SurveyExpert\Builders;

class ConditionBuilder
{
    protected $id;
    protected $field;
    protected $value;
    protected $operator;

    public function __construct($data = null)
    {
        $this->id = uniqid();

        if ($data) {
            $this->fromJson($data);
        }
    }

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

    public function toJson()
    {
        return json_encode([
            'field' => $this->field,
            'value' => $this->value,
            'operator' => $this->operator,
        ]);
    }

    public function fromJson($data)
    {
        $data = json_decode($data, true);
        $this->id = $data['id'] ?? uniqid();
        $this->field = $data['field'] ?? null;
        $this->value = $data['value'] ?? null;
        $this->operator = $data['operator'] ?? null;
    }
}
