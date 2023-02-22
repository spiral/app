<?php

declare(strict_types=1);

use Spiral\Cache\Storage\ArrayStorage;
use Spiral\Cache\Storage\FileStorage;

/**
 * Configuration for cache component.
 *
 * @link https://spiral.dev/docs/basics-cache
 */
return [

    /**
     * The default cache connection that gets used while using this caching library.
     */
    'default' => env('CACHE_STORAGE', 'file'),

    /**
     * Aliases, if you want to use domain specific storages.
     */
    'aliases' => [
        // 'user-data' => [
        //     'storage' => 'file',
        //     'prefix' => 'user_'
        // ],
        // 'blog-data' => 'file',
    ],

    /**
     * Here you may define all of the cache "storages" for your application as well as their types.
     */
    'storages' => [

        'local' => [
            // Alias for ArrayStorage type
            'type' => 'array',
        ],

        'file' => [
            // Alias for FileStorage type
            'type' => 'file',
            'path' => directory('runtime') . 'cache',
        ],

    ],

    /**
     * Aliases for storage types
     */
    'typeAliases' => [
        'array' => ArrayStorage::class,
        'file' => FileStorage::class,
    ],
];
