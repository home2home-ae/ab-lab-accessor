<?php

namespace ABLab\Accessor;

use ABLab\Accessor\Cache\CacheEngineType;
use ABLab\Accessor\Cache\CacheInterface;
use ABLab\Accessor\Cache\InMemorySingleRequestCache;
use ABLab\Accessor\Request\GetTreatmentRequest;

class ABLabCaching implements CacheInterface
{
    private string $type;
    private array $config;
    private array $implementations = [];

    public function __construct(string $type, array $config = [])
    {
        $this->type = $type;
        $this->config = $config;
    }

    /**
     * Get caching implementation
     *
     * @param string $type
     * @param array $config
     * @return CacheInterface
     * @throws \Exception
     */
    private function getImplementation(string $type, array $config = []): CacheInterface
    {
        if (isset($this->implementations[$type])) {
            return $this->implementations[$type];
        }

        $implementation = match ($type) {
            CacheEngineType::REQUEST => InMemorySingleRequestCache::getInstance(),
            default => throw new \Exception('Not supported yet.'),
        };

        $this->implementations[$type] = $implementation;

        return $implementation;
    }

    /**
     * Create cache key
     *
     * @param GetTreatmentRequest $treatmentRequest
     * @return string
     * @throws \Exception
     */
    public function createCacheKey(GetTreatmentRequest $treatmentRequest): string
    {
        return $this->getImplementation($this->type, $this->config)->createCacheKey($treatmentRequest);
    }

    /**
     * Check if has cache key
     *
     * @param string $cacheKey
     * @return bool
     * @throws \Exception
     */
    public function hasCacheKey(string $cacheKey): bool
    {
        return $this->getImplementation($this->type, $this->config)->hasCacheKey($cacheKey);
    }

    /**
     * Cache Treatment response
     *
     * @param string $cacheKey
     * @param string $response
     * @throws \Exception
     */
    public function cacheTreatmentResponse(string $cacheKey, string $response): void
    {
        $this->getImplementation($this->type, $this->config)->cacheTreatmentResponse($cacheKey, $response);
    }

    /**
     * Get Cached response
     *
     * @param string $cacheKey
     * @return string
     * @throws \Exception
     */
    public function getCachedResponse(string $cacheKey): string
    {
        return $this->getImplementation($this->type, $this->config)->getCachedResponse($cacheKey);
    }

    /**
     * Cache to raw storage for debugging / testing etc.
     *
     * @param GetTreatmentRequest $treatmentRequest
     * @param string $cacheKey
     * @param string $response
     * @throws \Exception
     */
    public function cacheToRawStorage(GetTreatmentRequest $treatmentRequest, string $cacheKey, string $response): void
    {
        $this->getImplementation($this->type, $this->config)->cacheToRawStorage($treatmentRequest, $cacheKey, $response);
    }
}