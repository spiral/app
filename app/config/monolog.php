<?php

declare(strict_types=1);

use Monolog\Logger;

return [
    'globalLevel' => Logger::toMonologLevel(env('MONOLOG_DEFAULT_LEVEL', Logger::DEBUG)),

    'handlers' => [
        'stdout' => [
            [
                'class' => \Monolog\Handler\SyslogHandler::class,
                'options' => [
                    'ident' => 'app',
                    'facility' => LOG_USER,
                ]
            ]
        ],
    ],
    'processors' => [
        'stdout' => [
            [
                'class'   => \Monolog\Processor\PsrLogMessageProcessor::class,
                'options' => [
                    'dateFormat' => 'Y-m-d\TH:i:s.uP'
                ]
            ],
        ],
    ],
];
