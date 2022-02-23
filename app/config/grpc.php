<?php

declare(strict_types=1);

return [
    /**
     * Path to protoc-gen-php-grpc library.
     * You can download the binary here: https://github.com/roadrunner-server/roadrunner/releases
     * Default: null
     */
    // 'binaryPath' => directory('root').'protoc-gen-php-grpc',

    /**
     * Paths to proto files, that should be compiled into PHP by "grpc:generate" console command.
     */
    'services' => [
        // directory('root').'proto/echo.proto',
    ],
];
