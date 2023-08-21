<?php

declare(strict_types=1);

/**
 * Configuration for gRPC.
 *
 * @link https://spiral.dev/docs/grpc-configuration#configuration
 */
return [
    /**
     * Path to protoc-gen-php-grpc library.
     * You can download the binary here: https://github.com/roadrunner-server/roadrunner/releases
     * Default: null
     */
    'binaryPath' => directory('root') . 'protoc-gen-php-grpc',

    /**
     * Path, where generated DTO files put.
     * Default: null
     */
    'generatedPath' => directory('root') . '/generated',

    /**
     * Base namespace for generated proto files.
     * Default: null
     */
    'namespace' => 'GRPC',

    /**
     * Paths to proto files, that should be compiled into PHP by "grpc:generate" console command.
     */
    'services' => [
        directory('root') . '/proto/service.proto'
    ],

    /**
     * Root path for all proto files in which imports will be searched.
     * Default: null
     */
    'servicesBasePath' => directory('root') . '/proto',
];
