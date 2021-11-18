<?php

declare(strict_types=1);

use Cycle\Database;

return [
    /**
     * Default database connection
     */
    'default' => env('DB_DEFAULT', 'default'),

    /**
     * The Cycle/Database module provides support to manage multiple databases
     * in one application, use read/write connections and logically separate
     * multiple databases within one connection using prefixes.
     *
     * To register a new database simply add a new one into
     * "databases" section below.
     */
    'databases' => [
        'default' => [
            'driver' => 'sqlite',
        ],
    ],

    /**
     * Each database instance must have an associated connection object.
     * Connections used to provide low-level functionality and wrap different
     * database drivers. To register a new connection you have to specify
     * the driver class and its connection options.
     */
    'drivers' => [
        'sqlite' => new Database\Config\SQLiteDriverConfig(
            connection: new Database\Config\SQLite\FileConnectionConfig(
                database: env('DB_DATABASE', __DIR__.'./../../runtime/database.sqlite')
            ),
            queryCache: true,
        ),
        'mysql' => new Database\Config\MySQLDriverConfig(
            connection: new Database\Config\MySQL\TcpConnectionConfig(
                database: env('DB_DATABASE', 'forge'),
                host: env('DB_HOST', '127.0.0.1'),
                port: (int)env('DB_PORT', 3306),
                user: env('DB_USERNAME', 'forge'),
                password: env('DB_PASSWORD', ''),
            ),
            queryCache: true
        ),
        'postgres' => new Database\Config\PostgresDriverConfig(
            connection: new Database\Config\Postgres\TcpConnectionConfig(
                database: env('DB_DATABASE', 'forge'),
                host: env('DB_HOST', '127.0.0.1'),
                port: (int)env('DB_PORT', 5432),
                user: env('DB_USERNAME', 'forge'),
                password: env('DB_PASSWORD', ''),
            ),
            schema: 'public',
            queryCache: true,
        ),
    ],
];
