<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JSON Source URL
    |--------------------------------------------------------------------------
    |
    | The source URL yielding a list of disposable email domains. Change this
    | to whatever source you like. Just make sure it returns a JSON array.
    |
    | A sensible default is provided using Rawgit's services. Rawgit is
    | a free service, so there are no uptime or support guarantees.
    |
    */

    'source' => 'https://rawgit.com/andreis/disposable-email-domains/master/domains.json',

    /*
    |--------------------------------------------------------------------------
    | Storage Path
    |--------------------------------------------------------------------------
    |
    | The location where the retrieved domains list should be stored locally.
    | The path should be accessible and writable by the web server. A good
    | place for storing the list is in the framework's own storage path.
    |
    */

    'storage' => resource_path('data/disposable_domains.json'),

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may define whether the disposable domains list should be cached.
    | If you disable caching or when the cache is empty, the list will be
    | fetched from local storage instead.
    |
    | You can optionally specify an alternate cache connection or modify the
    | cache key as desired.
    |
    */

    'cache' => [
        'enabled' => false,
        'store' => 'default',
        'key' => 'disposable_email.domains',
    ],

];