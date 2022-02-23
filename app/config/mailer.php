<?php

declare(strict_types=1);

return [
    /**
     * The default mailer that is used to send any email messages sent by your application.
     * @see https://symfony.com/doc/current/mailer.html#using-built-in-transports
     */
    'dsn' => env('MAILER_DSN', 'smtp://user:pass@mailhog:25'),

    /**
     * Global "From" Address
     */
    'from' => env('MAILER_FROM', 'Spiral <sendit@local.host>'),

    /**
     * A queue connection in that any email messages will be pushed.
     */
    'queueConnection' => env('MAILER_QUEUE_CONNECTION'),
    'queue' => env('MAILER_QUEUE', 'local'),
];
