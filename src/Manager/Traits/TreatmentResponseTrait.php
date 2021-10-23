<?php

namespace ABLab\Accessor\Manager\Traits;

use ABLab\Accessor\Response\TreatmentResponse;

trait TreatmentResponseTrait
{
    protected function defaultTreatmentResponse(string $treatmentType, ?string $defaultTreatment = null)
    {
        if (null === $defaultTreatment) {
            $defaultTreatment = $this->getDefaultTreatment();
        }

        $response = new TreatmentResponse($defaultTreatment, $treatmentType);

        $response->setRawResponse($this->rawFeature)
            ->setArrayConvertedResponse($this->feature)
            ->setTreatmentRequest($this->treatmentRequest);

        return $response;
    }
}