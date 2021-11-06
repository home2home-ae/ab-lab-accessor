<?php

namespace ABLab\Accessor\Cache;

use ABLab\Accessor\Cache\Traits\RawStorageDebugger;
use ABLab\Accessor\Request\GetTreatmentRequest;
use ABLab\Accessor\Cache\Traits\CreateCacheKey;

class InMemorySingleRequestCache implements CacheInterface
{
    use CreateCacheKey,
        RawStorageDebugger;

    private array $storage = [];

    /**
     * Check if it has the cache key
     *
     * @param string $cacheKey
     * @return bool
     */
    public function hasCacheKey(string $cacheKey): bool
    {
        return isset($this->storage[$cacheKey]);
    }

    /**
     * Cache treatment response
     *
     * @param string $cacheKey
     * @param string $response
     */
    public function cacheTreatmentResponse(string $cacheKey, string $response): void
    {
        $this->storage[$cacheKey] = $response;
    }

    /**
     * Get cached response
     *
     * @param string $cacheKey
     * @return string
     */
    public function getCachedResponse(string $cacheKey): string
    {
        return $this->storage[$cacheKey];
    }
}