<?php

namespace ABLab\Accessor\Cache\Traits;

use ABLab\Accessor\Request\GetTreatmentRequest;

trait RawStorageDebugger
{
    private array $rawStorage = [];

    /**
     * Cache response to raw storage, ideal for debugging
     *
     * @param GetTreatmentRequest $treatmentRequest
     * @param string $cacheKey
     * @param string $response
     */
    public function cacheToRawStorage(GetTreatmentRequest $treatmentRequest, string $cacheKey, string $response): void
    {
        $this->rawStorage[$cacheKey] = [
            'request' => $this->getTreatmentRequestCacheables($treatmentRequest),
            'response' => $response
        ];
    }
}