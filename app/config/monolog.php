<?php

declare(strict_types=1);

use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /**
     * Monolog supports the logging levels described by RFC 5424.
     *
     * @see https://github.com/Seldaek/monolog/blob/main/doc/01-usage.md#log-levels
     */
    'globalLevel' => Logger::toMonologLevel(env('MONOLOG_DEFAULT_LEVEL', Logger::DEBUG)),

    /**
     * @see https://github.com/Seldaek/monolog/blob/main/doc/02-handlers-formatters-processors.md#handlers
     */
    'handlers' => [
        'default' => [
            [
                'class' => 'log.rotate',
                'options' => [
                    'filename' => directory('runtime').'logs/app.log',
                    'level' => Logger::DEBUG,
                ],
            ],
        ],
        'stderr' => [
            \Monolog\Handler\ErrorLogHandler::class,
        ],
        'stdout' => [
            [
                'class' => SyslogHandler::class,
                'options' => [
                    'ident' => 'app',
                    'facility' => LOG_USER,
                ],
            ],
        ],
    ],

    /**
     * Processors allows adding extra data for all records.
     *
     * @see https://github.com/Seldaek/monolog/blob/main/doc/02-handlers-formatters-processors.md#processors
     */
    'processors' => [
        'default' => [
            // ...
        ],
        'stdout' => [
            [
                'class' => PsrLogMessageProcessor::class,
                'options' => [
                    'dateFormat' => 'Y-m-d\TH:i:s.uP',
                ],
            ],
        ],
    ],
];
