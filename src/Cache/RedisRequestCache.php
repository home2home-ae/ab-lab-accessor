<?php

namespace ABLab\Accessor\Cache;

use ABLab\Accessor\Cache\Traits\CreateCacheKey;
use ABLab\Accessor\Cache\Traits\RawStorageDebugger;
use ABLab\Accessor\Request\GetTreatmentRequest;
use Illuminate\Support\Facades\Redis;

class RedisRequestCache implements CacheInterface
{
    use CreateCacheKey,
        RawStorageDebugger;

    private string $connection;

    public function __construct(string $connection)
    {
        $this->connection = $connection;
    }

    public function cacheTreatmentResponse(string $cacheKey, string $response): void
    {
        // expire key after 10 minutes
        Redis::connection($this->connection)->set($cacheKey, $response, 'EX', 60 * 10);
    }

    public function hasCacheKey(string $cacheKey): bool
    {
        return (
            Redis::connection($this->connection)->get($cacheKey) !== null &&
            Redis::connection($this->connection)->get($cacheKey) !== "" &&
            Redis::connection($this->connection)->get($cacheKey) !== false
        );
    }

    public function getCachedResponse(string $cacheKey): string
    {
        return Redis::connection($this->connection)->get($cacheKey);
    }
}