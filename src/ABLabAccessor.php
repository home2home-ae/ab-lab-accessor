<?php

namespace ABLab\Accessor;

use ABLab\Accessor\Data\FeatureRetrieverImplementation;
use ABLab\Accessor\Manager\ApiFeatureRetriever;
use ABLab\Accessor\Manager\FeatureRetrieverInterface;
use ABLab\Accessor\Manager\RedisFeatureRetriever;
use ABLab\Accessor\Request\GetTreatmentRequest;
use ABLab\Accessor\Response\TreatmentResponse;
use Exception;

class ABLabAccessor implements ABLabAccessorInterface
{
    protected array $config = [];
    protected array $implementations = [
        FeatureRetrieverImplementation::REDIS => null,
        FeatureRetrieverImplementation::API => null
    ];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getTreatment(GetTreatmentRequest $treatmentRequest): TreatmentResponse
    {
        /** @var FeatureRetrieverInterface $manager */
        $manager = $this->implementations[$this->config['implementation']];
        if (null === $manager) {
            $manager = $this->getFeatureRetriever($this->config['implementation']);
            $this->implementations[$this->config['implementation']] = $manager;
        }

        return $manager->getTreatment($treatmentRequest);
    }

    private function getFeatureRetriever(string $implementation)
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
        return new ApiFeatureRetriever();
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
}