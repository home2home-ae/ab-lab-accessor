<?php

namespace ABLab\Accessor\Manager;

use ABLab\Accessor\Data\TreatmentType;
use ABLab\Accessor\Request\GetTreatmentRequest;
use ABLab\Accessor\Response\TreatmentResponse;
use Illuminate\Support\Facades\Http;

class ApiFeatureRetriever implements FeatureRetrieverInterface
{
    const API_ENDPOINT_GET_TREATMENT = 'get-treatment';

    private array $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getTreatment(GetTreatmentRequest $treatmentRequest): TreatmentResponse
    {
        $response = $this->getTreatmentResponse($treatmentRequest);

        $jsonResponse = json_decode($response, true);

        if (false === $jsonResponse || null === $jsonResponse) {
            return new TreatmentResponse($treatmentRequest->getDefaultTreatment(), TreatmentType::INVALID_DATA);
        }

        return new TreatmentResponse($jsonResponse['response']['treatment'], $jsonResponse['response']['treatmentType']);
    }

    /**
     * Make base url
     *
     * @param string $baseUrl
     * @return string
     */
    protected function getTreatmentEndpointUrl(string $baseUrl): string
    {
        return $baseUrl . '/' . ApiFeatureRetriever::API_ENDPOINT_GET_TREATMENT;
    }

    protected function getTreatmentResponse(GetTreatmentRequest $treatmentRequest)
    {
        if (isset($this->config['username']) && isset($this->config['password'])) {
            $client = Http::withBasicAuth($this->config['username'], $this->config['password']);
        } else {
            $client = Http::withToken($this->config['token']);
        }

        $url = $this->getTreatmentEndpointUrl($this->config['base_url']);

        return $client->get($url, $treatmentRequest->toArray())->body();
    }
}