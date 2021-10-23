<?php

namespace ABLab\Accessor\Manager;

use ABLab\Accessor\Request\GetTreatmentRequest;
use ABLab\Accessor\Response\TreatmentResponse;

class ApiFeatureRetriever implements FeatureRetrieverInterface
{

    public function getTreatment(GetTreatmentRequest $treatmentRequest): TreatmentResponse
    {
        return new TreatmentResponse(null, [], 'C', $treatmentRequest);
    }
}