<?php

namespace App\Classes\SurveyExpert\Parts\Builders;

class PermissionsBuilder
{
    protected $permissions = [
        'video' => false,
        'audio' => false,
        'location' => false,
    ];

    public function requireVideo($required = true)
    {
        $this->permissions['video'] = $required;
        return $this;
    }

    public function requireAudio($required = true)
    {
        $this->permissions['audio'] = $required;
        return $this;
    }

    public function requireLocation($required = true)
    {
        $this->permissions['location'] = $required;
        return $this;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function toJson()
    {
        return json_encode($this->permissions);
    }

    public function fromJson($data)
    {
        $data = json_decode($data, true);
        $this->permissions['video'] = $data['video'] ?? false;
        $this->permissions['audio'] = $data['audio'] ?? false;
        $this->permissions['location'] = $data['location'] ?? false;
    }
}
