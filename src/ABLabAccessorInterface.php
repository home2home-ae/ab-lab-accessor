<?php

namespace ABLab\Accessor;

use ABLab\Accessor\Manager\FeatureRetrieverInterface;
use ABLab\Accessor\Request\GetTreatmentRequest;
use ABLab\Accessor\Response\TreatmentResponse;

interface ABLabAccessorInterface
{
    public function withImplementation(string $implementation): FeatureRetrieverInterface;

    public function getTreatment(GetTreatmentRequest $treatmentRequest): string;

    public function getTreatmentResponse(GetTreatmentRequest $treatmentRequest): TreatmentResponse;
}