<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\SocketHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Monolog\Level;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Monolog\Config\MonologConfig;

/**
 * The bootloader is responsible for configuring the application specific loggers.
 *
 * @link https://spiral.dev/docs/basics-logging
 */
final class LoggingBootloader extends Bootloader
{
    public function __construct(
        private readonly ConfiguratorInterface $config,
    ) {}

    public function init(MonologBootloader $monolog, EnvironmentInterface $env): void
    {
        // HTTP level errors
        $monolog->addHandler(
            channel: ErrorHandlerMiddleware::class,
            handler: $monolog->logRotate(
                directory('runtime') . 'logs/http.log',
            ),
        );

        // app level errors
        $monolog->addHandler(
            channel: MonologConfig::DEFAULT_CHANNEL,
            handler: $monolog->logRotate(
                filename: directory('runtime') . 'logs/error.log',
                level: Level::Error,
                maxFiles: 25,
                bubble: false,
            ),
        );

        // debug and info messages via global LoggerInterface
        $monolog->addHandler(
            channel: MonologConfig::DEFAULT_CHANNEL,
            handler: $monolog->logRotate(
                filename: directory('runtime') . 'logs/debug.log',
            ),
        );

        // JSON records over TCP, e.g. to Buggregator; select with MONOLOG_DEFAULT_CHANNEL=remote.
        // The handler connects on the first record, and a failed connection does not break the application.
        $monolog->addHandler(
            channel: 'remote',
            handler: new WhatFailureGroupHandler([
                (new SocketHandler((string) $env->get('MONOLOG_REMOTE_URL', '127.0.0.1:9913')))
                    ->setFormatter(new JsonFormatter()),
            ]),
        );
    }
}
