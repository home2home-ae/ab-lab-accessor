<?php

namespace ABLab\Accessor\Cache;

use ABLab\Accessor\Request\GetTreatmentRequest;

class InMemorySingleRequestCache implements CacheInterface
{
    private static CacheInterface|null $instance = null;

    private array $storage = [];
    private array $rawStorage = [];

    public static function getInstance(): CacheInterface
    {
        if (null == InMemorySingleRequestCache::$instance) {
            InMemorySingleRequestCache::$instance = new InMemorySingleRequestCache();
        }

        return InMemorySingleRequestCache::$instance;
    }

    /**
     * @param GetTreatmentRequest $treatmentRequest
     * @return array
     */
    private function getTreatmentRequestCacheables(GetTreatmentRequest $treatmentRequest): array
    {
        return array_filter($treatmentRequest->toArray());
    }

    public function createCacheKey(GetTreatmentRequest $treatmentRequest): string
    {
        return md5(json_encode($this->getTreatmentRequestCacheables($treatmentRequest)));
    }

    public function hasCacheKey(string $cacheKey): bool
    {
        return isset($this->storage[$cacheKey]);
    }

    public function cacheTreatmentResponse(string $cacheKey, string $response): void
    {
        $this->storage[$cacheKey] = $response;
    }

    public function getCachedResponse(string $cacheKey): string
    {
        return $this->storage[$cacheKey];
    }

    public function cacheToRawStorage(GetTreatmentRequest $treatmentRequest, string $cacheKey, string $response): void
    {
        $this->rawStorage[$cacheKey] = [
            'request' => $this->getTreatmentRequestCacheables($treatmentRequest),
            'response' => $response
        ];
    }
}