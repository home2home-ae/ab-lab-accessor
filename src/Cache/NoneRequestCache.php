<?php

namespace ABLab\Accessor\Cache;

use ABLab\Accessor\Cache\Traits\CreateCacheKey;
use ABLab\Accessor\Cache\Traits\RawStorageDebugger;
use ABLab\Accessor\Request\GetTreatmentRequest;

class NoneRequestCache implements CacheInterface
{
    use CreateCacheKey,
        RawStorageDebugger;

    public function cacheTreatmentResponse(string $cacheKey, string $response): void
    {
        // TODO: Implement cacheTreatmentResponse() method.
    }

    public function cacheToRawStorage(GetTreatmentRequest $treatmentRequest, string $cacheKey, string $response): void
    {
        // TODO: Implement cacheToRawStorage() method.
    }

    public function hasCacheKey(string $cacheKey): bool
    {
        return false;
    }

    public function getCachedResponse(string $cacheKey): string
    {
        throw new \Exception("None caching have no response");
    }
}