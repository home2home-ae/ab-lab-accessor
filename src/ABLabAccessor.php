<?php

namespace ABLab\Accessor;

use ABLab\Accessor\Cache\CacheInterface;
use ABLab\Accessor\Data\FeatureRetrieverImplementation;
use ABLab\Accessor\Manager\ApiFeatureRetriever;
use ABLab\Accessor\Manager\FeatureRetrieverInterface;
use ABLab\Accessor\Manager\RedisFeatureRetriever;
use ABLab\Accessor\Request\GetTreatmentRequest;
use ABLab\Accessor\Response\TreatmentResponse;
use Exception;

class ABLabAccessor implements ABLabAccessorInterface
{
    protected CacheInterface $cacheInterface;
    protected array $config = [];
    protected array $implementations = [
        FeatureRetrieverImplementation::REDIS => null,
        FeatureRetrieverImplementation::API => null
    ];
    protected string $appId;
    protected string $appStage;
    protected string $defaultTreatment;

    public function __construct(CacheInterface $cacheInterface, array $config)
    {
        $this->cacheInterface = $cacheInterface;
        $this->config = $config;
        $this->appId = $config['id'];
        $this->appStage = $config['stage'];
        $this->defaultTreatment = $config['defaultTreatment'];
    }

    /**
     * Core interface between A/B Lab and the world
     *
     * @param GetTreatmentRequest $treatmentRequest
     * @return TreatmentResponse
     * @throws Exception
     */
    public function getTreatmentResponse(GetTreatmentRequest $treatmentRequest): TreatmentResponse
    {
        /** @var FeatureRetrieverInterface $manager */
        $manager = $this->implementations[$this->config['implementation']];
        if (null === $manager) {
            $manager = $this->getFeatureRetriever($this->config['implementation']);
            $this->implementations[$this->config['implementation']] = $manager;
        }

        return $manager->getTreatment($treatmentRequest);
    }

    /**
     * Merge variables from config to treatment request
     *
     * @param GetTreatmentRequest $treatmentRequest
     * @return GetTreatmentRequest
     */
    private function mergeEnvironmentConfig(GetTreatmentRequest $treatmentRequest): GetTreatmentRequest
    {
        // resolve these automatically (appId, appStage), remove the extra parameters needed from user
        if (null === $treatmentRequest->getApplication()) {
            $treatmentRequest->setApplication($this->appId);
        }

        if (null === $treatmentRequest->getApplicationStage()) {
            $treatmentRequest->setApplicationStage($this->appStage);
        }

        if (null === $treatmentRequest->getDefaultTreatment()) {
            $treatmentRequest->setDefaultTreatment($this->defaultTreatment);
        }

        return $treatmentRequest;
    }

    /**
     * Get treatment as string, better for comparing, views
     *
     * @param GetTreatmentRequest $treatmentRequest
     * @return string
     * @throws Exception
     */
    public function getTreatment(GetTreatmentRequest $treatmentRequest): string
    {
        $treatmentRequest = $this->mergeEnvironmentConfig($treatmentRequest);

        // create cache key
        $cacheKey = $this->cacheInterface->createCacheKey($treatmentRequest);

        // check cache key exists
        if ($this->cacheInterface->hasCacheKey($cacheKey)) {
            // if exists send response
            return $this->cacheInterface->getCachedResponse($cacheKey);
        }

        $response = $this->getTreatmentResponse($treatmentRequest)->getTreatment();

        // save response with cache key
        $this->cacheInterface->cacheTreatmentResponse($cacheKey, $response);
        $this->cacheInterface->cacheToRawStorage($treatmentRequest, $cacheKey, $response);

        return $response;
    }

    /**
     * Wrapper to solve the local vs remote problem
     *
     * @param string $implementation
     * @return FeatureRetrieverInterface
     * @throws Exception
     */
    public function withImplementation(string $implementation): FeatureRetrieverInterface
    {
        return $this->getFeatureRetriever($implementation);
    }

    /**
     * Get FeatureRetriever based on the implementation
     *
     * @param string $implementation
     * @return FeatureRetrieverInterface
     * @throws Exception
     */
    private function getFeatureRetriever(string $implementation): FeatureRetrieverInterface
    {
        if (FeatureRetrieverImplementation::API === $implementation) {
            return $this->createApiManager();
        } elseif (FeatureRetrieverImplementation::REDIS === $implementation) {
            return $this->createRedisManager();
        }

        throw new Exception("Unsupported implementation: {$implementation}");
    }

    protected function createApiManager()
    {
        return new ApiFeatureRetriever($this->config['api']);
    }

    protected function createRedisManager()
    {
        $connectionName = md5(uniqid('ABLabACCESSOR-', true));
        $connection = $this->config['redis'];

        $this->setRedisConnection($connectionName, $connection);

        return new RedisFeatureRetriever($connectionName);
    }

    private static function setRedisConnection($connectionName, $connectionProperties)
    {
        config(["database.redis.{$connectionName}" => $connectionProperties]);
    }

    public function dd()
    {
        $data = [
            'instance' => $this,
            'cache' => app('ab-lab-cache')
        ];

        dd($data);
    }
}