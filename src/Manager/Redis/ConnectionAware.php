<?php

namespace ABLab\Accessor\Manager\Redis;

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
}