<?php

namespace ABLab\Accessor\Cache\Traits;

use ABLab\Accessor\Request\GetTreatmentRequest;

trait CreateCacheKey
{
    /**
     * Get all non-null values
     *
     * @param GetTreatmentRequest $treatmentRequest
     * @return array
     */
    private function getTreatmentRequestCacheables(GetTreatmentRequest $treatmentRequest): array
    {
        return array_filter($treatmentRequest->toArray());
    }

    /**
     * Create cache key for the treatment request
     *
     * @param GetTreatmentRequest $treatmentRequest
     * @return string
     */
    public function createCacheKey(GetTreatmentRequest $treatmentRequest): string
    {
        return md5(json_encode($this->getTreatmentRequestCacheables($treatmentRequest)));
    }
}