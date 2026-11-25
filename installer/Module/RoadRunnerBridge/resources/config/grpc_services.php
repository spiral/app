<?php

declare(strict_types=1);

use GRPC\Ping\PingServiceInterface;
use Spiral\Grpc\Client\Config\ConnectionConfig;
use Spiral\Grpc\Client\Config\ServiceConfig;

/**
 * Configuration for gRPC.
 *
 * @link https://spiral.dev/docs/grpc-configuration#configuration
 */
return [
    /**
     * Paths to the buf, protoc and protoc-gen-php-grpc binaries used by the "grpc:generate" console command.
     * Download them with `vendor/bin/dload get` (see dload.xml).
     * Default: null (looks for `<root>/<name>[.exe]`, then in PATH)
     */
    'bufPath' => null,
    'protocPath' => null,
    'pluginPath' => null,

    /**
     * Path, where generated DTO files put.
     * Default: null
     */
    'generatedPath' => directory('root') . 'generated',

    /**
     * Base namespace for generated proto files.
     * Default: null
     */
    'namespace' => 'GRPC',

    /**
     * Paths to proto files, that should be compiled into PHP by "grpc:generate" console command.
     */
    'services' => [
        directory('root') . 'proto/service.proto',
    ],

    /**
     * Root path for all proto files in which imports will be searched.
     * Default: null
     */
    'servicesBasePath' => directory('root') . 'proto',

    /**
     * Clients of remote gRPC services: each listed interface is bound in the container.
     *
     * @link https://github.com/spiral/grpc-client
     */
    'client' => [
        'interceptors' => [],
        'services' => [
            new ServiceConfig(
                connections: new ConnectionConfig(env('PING_SERVICE_HOST', '127.0.0.1:9001')),
                interfaces: [PingServiceInterface::class],
            ),
        ],
    ],
];
