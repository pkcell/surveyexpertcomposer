<?php

namespace Greenbit\SurveyExpert\Builders;

class SectionBuilder
{
    protected $id;
    protected $label;
    protected $description;
    protected $questions = [];

    public function __construct($data = null)
    {
        $this->id = uniqid();
        $this->label = new TranslatableBuilder();
        $this->description = new TranslatableBuilder();

        if ($data) {
            $this->fromJson($data);
        }
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

    public function addQuestion(callable $callback)
    {
        $question = new QuestionBuilder();
        $callback($question);
        $this->questions[$question->getId()] = $question;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label->getTranslations();
    }

    public function getDescription()
    {
        return $this->description->getTranslations();
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    public function updateQuestion($id, callable $callback)
    {
        if (isset($this->questions[$id])) {
            $callback($this->questions[$id]);
        }
        return $this;
    }

    public function reorderQuestions(array $order)
    {
        $orderedQuestions = [];
        foreach ($order as $id) {
            if (isset($this->questions[$id])) {
                $orderedQuestions[$id] = $this->questions[$id];
            }
        }
        $this->questions = $orderedQuestions;
        return $this;
    }

    public function toJson()
    {
        $questionsJson = [];
        foreach ($this->questions as $question) {
            $questionsJson[] = json_decode($question->toJson(), true);
        }
        return json_encode([
            'id' => $this->id,
            'label' => $this->label->getTranslations(),
            'description' => $this->description->getTranslations(),
            'questions' => $questionsJson
        ]);
    }

    public function fromJson($data)
    {
        $data = json_decode($data, true);
        $this->id = $data['id'] ?? uniqid();

        $this->label->setTranslations($data['label'] ?? []);
        $this->description->setTranslations($data['description'] ?? []);

        foreach ($data['questions'] ?? [] as $questionData) {
            $question = new QuestionBuilder(json_encode($questionData));
            $this->questions[$question->getId()] = $question;
        }
    }
}
