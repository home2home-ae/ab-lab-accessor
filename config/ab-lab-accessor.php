<?php

return [

    /**
     * Unique application ID for the application
     * This you can take from A/B Lab Web application
     */
    'id' => 'H2H-ADMIN',

    /**
     * Available options are: DEVELOPMENT, PRODUCTION
     * DEVELOPMENT: it's usually your staging / devo environment
     * PRODUCTION: it's usually your production environment
     */
    'stage' => 'DEVELOPMENT',

    /**
     * There are two possible values for the implementation: api, redis
     *
     * redis: mean redis implementation, best when ab-lab is deployed in the same VPC
     * api: it's better for microservices architecture
     */
    'implementation' => 'redis',


    /**
     * Setting for WEB API implementation
     *
     * API implementation is still not there, but this is the plan, we only need base url and token for authentication
     */
    'api' => [
        'base_url' => 'http://ab-test.loc/api',
        'token' => null,
    ],

    /**
     * Setting for REDIS API implementation
     *
     * REDIS implementation is complete and test is in progress, so far it looks good!
     */
    'redis' => [
        'host' => '127.0.0.1',
        'password' => null,
        'port' => '6379',
        'database' => 'ab_test_redis',
        'prefix' => 'ab_test_fx_'
    ]
];