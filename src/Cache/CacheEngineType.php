<?php

namespace ABLab\Accessor\Cache;

interface CacheEngineType
{
    const NONE = 'none';
    const REQUEST = 'request';
    const REDIS = 'redis';
    const FILE = 'file';
}