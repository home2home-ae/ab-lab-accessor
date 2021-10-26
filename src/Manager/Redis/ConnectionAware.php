<?php

namespace ABLab\Accessor\Manager\Redis;

use Illuminate\Redis\Connections\PhpRedisConnection;
use Illuminate\Redis\Connectors\PhpRedisConnector;

trait ConnectionAware
{
    protected string $connection;

    public function setConnectionName(string $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    public function getConnectionName(): string
    {
        return $this->connection;
    }

    public function getConnection(string $connectionName): PhpRedisConnection
    {
        $config = config('database.redis.' . $connectionName);

        $connector = new PhpRedisConnector();

        return $connector->connect($config, []);
    }
}