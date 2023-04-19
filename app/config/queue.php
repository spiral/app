<?php

declare(strict_types=1);

use App\Endpoint\Job\PingHandler;
use Spiral\Queue\Driver\SyncDriver;
use Spiral\RoadRunner\Jobs\Queue\MemoryCreateInfo;

return [
    /**
     *  Default queue connection name.
     */
    'default' => env('QUEUE_CONNECTION', 'ping-queue'),

    /**
     *  Aliases for queue connections, if you want to use domain specific queues.
     */
    'aliases' => [
        'ping-queue'   => 'in-memory',
        'rating-queue' => 'sync',
    ],

    /**
     * Queue connections.
     * Drivers: "sync", "roadrunner".
     *
     * @link https://spiral.dev/docs/queue-configuration/#3.7/en
     */
    'connections' => [
        'sync' => [
            // Job will be handled immediately without queueing
            'driver' => 'sync',
        ],
        'in-memory' => [
            'driver'   => 'roadrunner',
            'pipeline' => 'memory',
        ],
    ],

    /**
     * You can create dynamic pipelines for RoadRunner.
     *
     * @link https://spiral.dev/docs/queue-roadrunner#declaring-pipelines-in-configuration-file
     * Here is a list of all available queue {@link https://roadrunner.dev/docs/queues-overview#creating-a-new-queue}
     */
    'pipelines' => [
        'memory' => [
            'connector' => new MemoryCreateInfo('local'),
            'consume'   => true,
        ],
    ],

    'driverAliases' => [
        'sync' => SyncDriver::class,
    ],

    /**
     * A serializer uses for converting job's payload from specified type to string and vice versa.
     *
     * @link https://spiral.dev/docs/queue-jobs/#job-payload-serialization
     */
    'defaultSerializer' => 'json',

    'registry' => [
        /**
         * Mapping of job names to job handlers for consumer. When a consumer receives a job, it will look for a
         * handler in this mapping.
         *
         * (QueueInterface)->push('ping', ["url" => "http://site.com"]);
         *
         * @link https://spiral.dev/docs/queue-jobs#job-handler-registry
         */
        'handlers' => [
            // 'ping' => PingHandler::class
        ],
        /**
         * Mapping of job names to serializers. When a job is pushed to the queue, it will be serialized using the
         * serializer specified in this mapping and then deserialized using the same serializer when the job is
         * handled.
         *
         * @link https://spiral.dev/docs/queue-jobs#changing-serializer
         */
        'serializers' => [
            // 'ping' => 'json',
        ],
    ],
];
