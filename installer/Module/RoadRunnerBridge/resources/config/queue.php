<?php

declare(strict_types=1);

use Spiral\Queue\Driver\SyncDriver;
use Spiral\RoadRunner\Jobs\Queue\AMQPCreateInfo;
use Spiral\RoadRunner\Jobs\Queue\BeanstalkCreateInfo;
use Spiral\RoadRunner\Jobs\Queue\MemoryCreateInfo;
use Spiral\RoadRunner\Jobs\Queue\SQSCreateInfo;
use Spiral\RoadRunnerBridge\Queue\Queue;

/**
 * Queue configuration
 *
 * @link https://spiral.dev/docs/queue-configuration and https://spiral.dev/docs/queue-roadrunner
 */
return [
    /**
     *  Default queue connection name
     */
    'default' => env('QUEUE_CONNECTION', 'in-memory'),

    /**
     *  Aliases for queue connections, if you want to use domain specific queues
     */
    'aliases' => [
        // 'mail-queue' => 'in-memory',
        // 'rating-queue' => 'sync',
    ],

    /**
     * Queue connections
     * Drivers: "sync", "roadrunner"
     *
     * @link https://spiral.dev/docs/queue-configuration
     */
    'connections' => [
        'sync' => [
            // Job will be handled immediately without queueing
            'driver' => 'sync',
        ],
        'in-memory' => [
            'driver' => 'roadrunner',
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
            // Run consumer for this pipeline on startup (by default)
            // You can pause consumer for this pipeline via console command
            // php app.php queue:pause local
            'consume' => true,
        ],
        // 'amqp' => [
        //     'connector' => new AMQPCreateInfo('bus', ...),
        //     // Don't consume jobs for this pipeline on start
        //     // You can run consumer for this pipeline via console command
        //     // php app.php queue:resume local
        //     'consume' => false
        // ],
        //
        // 'beanstalk' => [
        //     'connector' => new BeanstalkCreateInfo('bus', ...),
        // ],
        //
        // 'sqs' => [
        //     'connector' => new SQSCreateInfo('amazon', ...),
        // ],
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
            // 'ping' => \App\Endpoint\Job\Ping::class
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
            // \App\Endpoint\Job\Ping::class => 'json',
        ],
    ],

    /**
     * Spiral provides a way for developers to customize the behavior of their job processing pipeline through the use
     * of interceptors. An interceptor is a piece of code that is executed before or after a job is pushed or consumed,
     * and which allows developers to hook into the job processing pipeline to perform some action.
     *
     * @link https://spiral.dev/docs/queue-interceptors
     */
    'interceptors' => [
        // 'push' => [],
        // 'consume' => [],
    ],

    'driverAliases' => [
        'sync' => SyncDriver::class,
    ],
];
