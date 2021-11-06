<?php

namespace ABLab\Accessor\Cache;

use ABLab\Accessor\Cache\Traits\CreateCacheKey;
use ABLab\Accessor\Cache\Traits\RawStorageDebugger;
use ABLab\Accessor\Request\GetTreatmentRequest;
use Illuminate\Support\Facades\Storage;

class FileRequestCache implements CacheInterface
{
    use CreateCacheKey,
        RawStorageDebugger;

    const FOLDER = 'ab-lab-cache';
    const FILE_NAME = 'localstorage.json';

    const CACHED_DATA_KEY = 'keys';
    const CACHED_DATA_TIME = 'time';
    const CACHED_DATA_RESPONSE = 'response';

    private string $disk;
    private array $data;

    public function __construct(string $disk)
    {
        $this->disk = $disk;
        $this->data = $this->reread($this->getPath());

        if (empty($this->data)) {
            $this->data[FileRequestCache::CACHED_DATA_KEY] = [];
            $this->data[FileRequestCache::CACHED_DATA_TIME] = time();
            $this->persist($this->data);
        }
    }

    private function getPath(): string
    {
        return FileRequestCache::FOLDER . '/' . FileRequestCache::FILE_NAME;
    }

    private function reread(string $path)
    {
        if (Storage::disk($this->disk)->exists($path)) {
            return json_decode(Storage::disk($this->disk)->get($path), true);
        }

        $this->persist([]);

        return $this->reread($path);
    }

    private function persist(array $data, string $path = null)
    {
        if (null === $path) {
            $path = $this->getPath();
        }

        Storage::disk($this->disk)->put($path, json_encode($data));
    }

    private function invalidateTreatmentResponse(string $cacheKey)
    {
        // get keys
        $keys = $this->data[FileRequestCache::CACHED_DATA_KEY];

        // remove cache key
        unset($keys[$cacheKey]);

        // update data
        $this->data[FileRequestCache::CACHED_DATA_KEY] = $keys;

        // persist data
        $this->persist($this->data);
    }

    /**
     * @param array $response
     * @return bool
     */
    private function shouldInvalidateTreatmentResponse(array $response): bool
    {
        return time() - $response[FileRequestCache::CACHED_DATA_TIME] > (60 * 10);
    }

    public function hasCacheKey(string $cacheKey): bool
    {
        if (!isset($this->data[FileRequestCache::CACHED_DATA_KEY][$cacheKey])) {
            return false;
        }

        $response = (array)$this->data[FileRequestCache::CACHED_DATA_KEY][$cacheKey];

        // if response is 10 minutes old, invalidate
        if ($this->shouldInvalidateTreatmentResponse($response)) {
            $this->invalidateTreatmentResponse($cacheKey);
            return false;
        }

        return true;
    }

    public function cacheTreatmentResponse(string $cacheKey, string $response): void
    {
        $this->data[FileRequestCache::CACHED_DATA_KEY][$cacheKey] = [
            FileRequestCache::CACHED_DATA_RESPONSE => $response,
            FileRequestCache::CACHED_DATA_TIME => time()
        ];
        $this->persist($this->data);
    }

    public function getCachedResponse(string $cacheKey): string
    {
        return $this->data[FileRequestCache::CACHED_DATA_KEY][$cacheKey][FileRequestCache::CACHED_DATA_RESPONSE];
    }
}