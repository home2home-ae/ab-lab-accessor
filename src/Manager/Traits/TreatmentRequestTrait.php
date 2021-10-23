<?php

namespace ABLab\Accessor\Manager\Traits;

trait TreatmentRequestTrait
{
    private function getFeatureName(): string
    {
        return $this->treatmentRequest->getFeatureName();
    }

    private function getDefaultTreatment()
    {
        return $this->treatmentRequest->getDefaultTreatment();
    }

    private function getApplicationStage()
    {
        return $this->treatmentRequest->getApplicationStage();
    }

    private function getApplication()
    {
        return $this->treatmentRequest->getApplication();
    }

    private function getEntityId()
    {
        return $this->treatmentRequest->getEntityId();
    }
}