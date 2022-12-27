<?php

declare(strict_types=1);

use Spiral\Queue\Driver\SyncDriver;

return [
    /**
     *  Default queue connection name
     */
    'default' => env('QUEUE_CONNECTION', 'sync'),

    /**
     *  Aliases for queue connections, if you want to use domain specific queues
     */
    'aliases' => [
        // 'mail-queue' => 'roadrunner',
        // 'rating-queue' => 'sync',
    ],

    /**
     * Queue connections
     */
    'connections' => [
        'sync' => [
            // Job will be handled immediately without queueing
            'driver' => 'sync',
        ],
    ],

    'driverAliases' => [
        'sync' => SyncDriver::class,
    ],

    'registry' => [
        'handlers' => [],
    ],
];
