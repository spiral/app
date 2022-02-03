<?php

declare(strict_types=1);

return [
    /**
     * Path to protoc-gen-php-grpc library.
     * Default: null
     */
    'binaryPath' => null,
    // 'binaryPath' => __DIR__.'/../../protoc-gen-php-grpc',

    'services' => [
        __DIR__ . '/../../proto/echo.proto',
    ],
];
