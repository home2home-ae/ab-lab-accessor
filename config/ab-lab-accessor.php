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
     * Setting for WEB API implementation
     *
     * API implementation is still not there, but this is the plan, we only need base url and token for authentication
     */
    'api' => [
        'base_url' => env('AB_LAB_API_URL', 'http://ab-lab.loc/api'),
        'token' => env('AB_LAB_API_TOKEN', null),
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
