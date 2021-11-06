<?php

return [

    /**
     * Unique application ID for the application
     * This you can take from A/B Lab Web application
     */
    'id' => env('AB_LAB_ID', 'H2H-ADMIN'),

    /**
     * Available options are: DEVELOPMENT, PRODUCTION
     * DEVELOPMENT: it's usually your staging / devo environment
     * PRODUCTION: it's usually your production environment
     */
    'stage' => env('AB_LAB_ENV', 'DEVELOPMENT'),

    /**
     * Available options are: C, T1, T2, T3
     *
     * C: Means in worst case you want to fall back to old flow
     * T1: Means in worst case you want to fall back to one of the new flow
     * T2: Means in worst case you want to fall back to one of the new flow
     * T3: Means in worst case you want to fall back to one of the new flow
     */
    'defaultTreatment' => env('AB_LAB_DEFAULT_TREATMENT', 'C'),

    /**
     * There are two possible values for the implementation: api, redis
     *
     * redis: mean redis implementation, best when ab-lab is deployed in the same VPC
     * api: it's better for microservices architecture
     */
    'implementation' => env('AB_LAB_IMPLEMENTATION', 'redis'),

    /**
     * Local cache implementation: request, redis, file
     *
     * request: mean for one request every feature treatment would be cached (In memory hash-map)
     * redis: for 10 minutes all the request and treatment would be cached in redis
     * file: for 10 minutes all the request and treatment would be cached on file
     */
    'cache' => env('AB_LAB_CACHE', 'request'),

    /**
     * Local cache implementation config in case cache is redis or file
     *
     * redis-connection: local redis connection to use for caching
     * path: local file path to be usef for file caching
     */
    'cache-config' => [
        'redis-connection' => env('AB_LAB_CACHE_REDIS_CONNECTION', 'default'),
        'path' => env('AB_LAB_CACHE_FILE_PATH', storage_path('/ab-lab-cache/')),
    ],

    /**
     * Setting for WEB API implementation
     *
     * API implementation is still not there, but this is the plan, we only need base url and token for authentication
     */
    'api' => [
        'base_url' => env('AB_LAB_API_URL', 'http://ab-lab.loc/api'),
        'token' => env('AB_LAB_API_TOKEN', null),
        'username' => env('AB_LAB_API_USERNAME', 'admin'),
        'password' => env('AB_LAB_API_PASSWORD', 'admin'),
    ],

    /**
     * Setting for REDIS API implementation
     *
     * REDIS implementation is complete and test is in progress, so far it looks good!
     */
    'redis' => [
        'host' => env('AB_LAB_REDIS_HOST', '127.0.0.1'),
        'password' => env('AB_LAB_REDIS_PASSWORD', null),
        'port' => env('AB_LAB_REDIS_PORT', '6379'),
        'database' => env('AB_LAB_REDIS_DB', 'ab_test_redis'),
        'prefix' => env('AB_LAB_REDIS_PREFIX', 'ab_test_fx_')
    ]
];
