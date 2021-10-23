<?php

namespace ABLab\Accessor\Manager;

use ABLab\Accessor\Request\GetTreatmentRequest;
use ABLab\Accessor\Response\TreatmentResponse;

interface FeatureRetrieverInterface
{
    public function getTreatment(GetTreatmentRequest $treatmentRequest): TreatmentResponse;
}