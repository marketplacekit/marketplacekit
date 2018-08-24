<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Disabling cache
    |--------------------------------------------------------------------------
    |
    | By setting this value to false, the cache will be disabled completely.
    | This may be useful for debugging purposes.
    |
    */
    'active' => env('LADA_CACHE_ACTIVE', true),

    /*
    |--------------------------------------------------------------------------
    | Redis prefix
    |--------------------------------------------------------------------------
    |
    | This prefix will be prepended to all items in Redis store.
    | Do not change this value in production, it will cause unexpected behavior.
    |
    */
    'prefix' => 'lada:',

    /*
    |--------------------------------------------------------------------------
    | Expiration time
    |--------------------------------------------------------------------------
    |
    | By default, if this value is set to null, cached items will never expire.
    | If you are afraid of dead data or if you care about disk space, it may
    | be a good idea to set this value to something like 604800 (7 days).
    |
    */
    'expiration-time' => null,

    /*
    |--------------------------------------------------------------------------
    | Cache granularity
    |--------------------------------------------------------------------------
    |
    | If you experience any issues while using the cache, try to set this value
    | to false. This will tell the cache to use a lower granularity and not
    | consider the row primary keys when creating the tags for a database query.
    | Since this will dramatically reduce the efficiency of the cache, it is
    | not recommended to do so in production environment.
    |
    */
    'consider-rows' => true,


    /*
    |--------------------------------------------------------------------------
    | Include tables
    |--------------------------------------------------------------------------
    |
    | If you want to cache only specific tables, put the table names into this
    | array. Then as soon as a query contains a table which is not specified in
    | here, it will not be cached. If you have this feature enabled, the value
    | of "exclude-tables" will be ignored and has no effect.
    |
    | Instead of hard coding table names in the configuration, it is a good
    | practice to initialize a new model instance and get the table name from
    | there like in the following example:
    |
    | 'include-tables' => [
    |     (new \App\Models\User())->getTable(),
    |     (new \App\Models\Post())->getTable(),
    | ],
    |
    */
    'include-tables' => [],

    /*
    |--------------------------------------------------------------------------
    | Exclude tables
    |--------------------------------------------------------------------------
    |
    | If you want to cache all tables but some specific ones, put them into this
    | array. As soon as a query contains at least one table specified in here, it
    | will not be cached.
    |
    */
    'exclude-tables' => [],

];