<?php

namespace Greenbit\SurveyExpert\Providers;

use Exception;
use Greenbit\SurveyExpert\Builders\SurveyBuilder;

class SurveyProvider
{
    protected $builder;
    protected $surveyId;
    protected $externalApiUrl;

    public function __construct($surveyId = null)
    {
        if ($surveyId) {
            $this->surveyId = $surveyId;
            $this->initiate();
        }

        $this->externalApiUrl = env('SURVEY_EXPERT_BASE_URL');
    }

    public static function forSurveyId($surveyId)
    {
        return new static($surveyId);
    }

    public function create(SurveyBuilder $builder)
    {
        $this->builder = $builder;
        $response = $this->sendRequest('POST', $this->externalApiUrl, $this->builder->toJson());

        if (isset($response['id'])) {
            $this->surveyId = $response['id'];
            return $this->surveyId;
        }

        throw new Exception('Failed to create survey on the external system.');
    }

    protected function initiate()
    {
        $url = $this->externalApiUrl . '/' . $this->surveyId;
        $response = $this->sendRequest('GET', $url);

        if ($response) {
            $this->builder = new SurveyBuilder();
            $this->builder->fromJson(json_encode($response));
        } else {
            throw new Exception('Failed to retrieve survey from the external system.');
        }
    }

    public function getBuilder()
    {
        if (!$this->builder) {
            throw new Exception('SurveyExpert builder is not initialized.');
        }
        return $this->builder;
    }

    public function update()
    {
        if (!$this->surveyId) {
            throw new Exception('SurveyExpert ID is not set. Cannot update.');
        }

        $url = $this->externalApiUrl . '/' . $this->surveyId;
        $response = $this->sendRequest('PUT', $url, $this->builder->toJson());

        if (!$response || !isset($response['success']) || !$response['success']) {
            throw new Exception('Failed to update survey on the external system.');
        }

        return true;
    }

    public function deploy()
    {
        if (!$this->surveyId) {
            throw new Exception('SurveyExpert ID is not set. Cannot deploy.');
        }

        $url = $this->externalApiUrl . '/' . $this->surveyId . '/deploy';
        $response = $this->sendRequest('POST', $url);

        if (!$response || !isset($response['success']) || !$response['success']) {
            throw new Exception('Failed to deploy survey on the external system.');
        }

        return true;
    }

    protected function sendRequest($method, $url, $data = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);


        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Request Error: ' . curl_error($ch));
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
