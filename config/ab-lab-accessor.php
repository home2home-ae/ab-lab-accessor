<?php

return [

    // api, redis
    'implementation' => 'redis',


    'api' => [
        'base_url' => 'http://ab-test.loc/api',
        'token' => null,
    ],

    'redis' => [
        'host' => '127.0.0.1',
        'password' => null,
        'port' => '6379',
        'database' => 'ab_test_redis',
        'prefix' => 'ab_test_fx_'
    ]
];