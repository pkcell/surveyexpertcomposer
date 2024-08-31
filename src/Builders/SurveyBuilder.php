<?php

namespace Greenbit\SurveyExpert\Builders;

class SurveyBuilder
{
    protected $id;
    protected $name;
    protected $description;
    protected $sections = [];
    protected $permissions = [];
    protected $logoUrl;
    protected $primaryColor;
    protected $secondaryColor;

    public function __construct($id = null)
    {
        if ($id) {
            $this->id = $id;
        } else {
            $this->id = uniqid();
        }
    }

    public static function create($id = null)
    {
        return new static($id);
    }

    public function name(callable $callback)
    {
        $this->name = new TranslatableBuilder();
        $callback($this->name);
        return $this;
    }

    public function description(callable $callback)
    {
        $this->description = new TranslatableBuilder();
        $callback($this->description);
        return $this;
    }

    public function addSection(callable $callback)
    {
        $section = new SectionBuilder();
        $callback($section);
        $this->sections[$section->getId()] = $section;
        return $this;
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function updateSection($id, callable $callback)
    {
        if (isset($this->sections[$id])) {
            $callback($this->sections[$id]);
        }
        return $this;
    }

    public function toJson()
    {
        $sectionsJson = [];
        foreach ($this->sections as $section) {
            $sectionsJson[] = json_decode($section->toJson(), true);
        }
        return json_encode([
            'name' => $this->name->getTranslations(),
            'description' => $this->description->getTranslations(),
            'sections' => $sectionsJson
        ]);
    }

    public function fromJson($data)
    {
        $data = json_decode($data, true);
        $this->name = new TranslatableBuilder();
        $this->description = new TranslatableBuilder();

        $this->name->setTranslations($data['name'] ?? []);
        $this->description->setTranslations($data['description'] ?? []);

        foreach ($data['sections'] ?? [] as $sectionData) {
            $section = new SectionBuilder(json_encode($sectionData));
            $this->sections[$section->getId()] = $section;
        }
    }

    public function save()
    {
        $surveyProvider = new SurveyProvider();
        return $surveyProvider->create($this);
    }

    public function update()
    {
        $surveyProvider = new SurveyProvider($this->id);
        return $surveyProvider->update();
    }

    public function deploy()
    {
        $surveyProvider = new SurveyProvider($this->id);
        return $surveyProvider->deploy();
    }
}
