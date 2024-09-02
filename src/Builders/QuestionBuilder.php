<?php

namespace Greenbit\SurveyExpert\Builders;

class QuestionBuilder
{
    protected $id;
    protected $externalId;
    protected $type;
    protected $label;
    protected $description;
    protected $required = false;
    protected $options = [];
    protected $conditions = [];
    protected $max;
    protected $min;

    public function __construct($data = null)
    {
        $this->id = uniqid();
        $this->label = new TranslatableBuilder();
        $this->description = new TranslatableBuilder();

        if ($data) {
            $this->fromJson($data);
        }
    }

    public function externalId($id)
    {
        $this->externalId = $id;
        return $this;
    }

    public function type($type)
    {
        $supportedTypes = [
            'text', 'textarea', 'radio', 'checkbox', 'select', 'email', 'number',
            'date', 'time', 'date_time', 'file', 'password', 'video_record', 'audio_record', 'image',
            'rating_scale', 'likert_scale'
        ];
        if (!in_array($type, $supportedTypes)) {
            throw new \Exception('Unsupported question type');
        }
        $this->type = $type;
        return $this;
    }

    public function label(callable $callback)
    {
        $callback($this->label);
        return $this;
    }

    public function description(callable $callback)
    {
        $callback($this->description);
        return $this;
    }

    public function required($required = true)
    {
        $this->required = $required;
        return $this;
    }

    public function max($max)
    {
        $this->max = $max;
        return $this;
    }

    public function min($min)
    {
        $this->min = $min;
        return $this;
    }

    public function addOption($value, callable $callback = null)
    {
        $option = new TranslatableBuilder();
        if ($callback) {
            $callback($option);
        }
        $this->options[$value] = $option->getTranslations();
        return $this;
    }

    public function addCondition(callable $callback)
    {
        $condition = new ConditionBuilder();
        $callback($condition);
        $this->conditions[] = $condition;
        return $this;
    }

    public function evaluateConditions($answers)
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->evaluate($answers)) {
                return false;
            }
        }
        return true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getExternalId()
    {
        return $this->externalId;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getLabel()
    {
        return $this->label->getTranslations();
    }

    public function getDescription()
    {
        return $this->description->getTranslations();
    }

    public function isRequired()
    {
        return $this->required;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function toJson()
    {
        return json_encode([
            'id' => $this->id,
            'externalId' => $this->externalId,
            'type' => $this->type,
            'label' => $this->label->getTranslations(),
            'description' => $this->description->getTranslations(),
            'required' => $this->required,
            'options' => $this->options
        ]);
    }

    public function fromJson($data)
    {
        $data = json_decode($data, true);
        $this->id = $data['id'] ?? uniqid();
        $this->externalId = $data['externalId'] ?? null;
        $this->type = $data['type'] ?? null;

        $this->label->setTranslations($data['label'] ?? []);
        $this->description->setTranslations($data['description'] ?? []);
        $this->required = $data['required'] ?? false;
        $this->options = $data['options'] ?? [];
    }
}
