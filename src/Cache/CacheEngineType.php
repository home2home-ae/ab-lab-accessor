<?php

namespace ABLab\Accessor\Cache;

interface CacheEngineType
{
    const REQUEST = 'request';
    const REDIS = 'redis';
    const FILE = 'file';
}