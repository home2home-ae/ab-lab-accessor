<?php

namespace ABLab\Accessor\Cache;

use ABLab\Accessor\Request\GetTreatmentRequest;

interface CacheInterface
{
    public function createCacheKey(GetTreatmentRequest $treatmentRequest): string;

    public function hasCacheKey(string $cacheKey): bool;

    public function cacheTreatmentResponse(string $cacheKey, string $response): void;

    public function cacheToRawStorage(GetTreatmentRequest $treatmentRequest, string $cacheKey, string $response): void;

    public function getCachedResponse(string $cacheKey): string;
}