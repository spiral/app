<?php

declare(strict_types=1);

return [
    'dsn' => env('MAILER_DSN', ''), // all available DSN formats: https://symfony.com/doc/current/mailer.html#using-built-in-transports
    'pipeline' => env('MAILER_PIPELINE', 'local'),
    'from' => env('MAILER_FROM', 'Spiral <sendit@local.host>'),
    'queueConnection' => env('MAILER_QUEUE_CONNECTION'),
];
