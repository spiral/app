<?php

declare(strict_types=1);

use Spiral\Cache\Storage\ArrayStorage;
use Spiral\Cache\Storage\FileStorage;

return [

    'default' => env('CACHE_STORAGE', 'local'),

    /**
     *  Aliases for storages, if you want to use domain specific storages
     */
    'aliases' => [
        'user-data' => 'localMemory'
    ],

    'storages' => [

        'local' => [
            // Alias for ArrayStorage type
            'type' => 'array',
        ],

        'localMemory' => [
            'type' => ArrayStorage::class,
        ],

        'file' => [
            // Alias for FileStorage type
            'type' => 'file',
            'path' => __DIR__ . '/../../runtime/cache',
        ],

        'redis' => [
            'type' => 'roadrunner',
            'driver' => 'redis'
        ],

        'rr-local' => [
            'type' => 'roadrunner',
            'driver' => 'local'
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
