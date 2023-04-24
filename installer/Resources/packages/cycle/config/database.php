<?php

declare(strict_types=1);

use Cycle\Database\Config;

return [
    'logger' => [
        'default' => null,
        'drivers' => [
            // 'runtime' => 'stdout'
        ],
    ],

    /**
     * Default database connection
     */
    'default' => 'default',

    /**
     * The cycle/database package provides support to manage multiple databases
     * in one application, use read/write connections and logically separate
     * multiple databases within one connection using prefixes.
     *
     * To register a new database simply add a new one into
     * "databases" section below.
     */
    'databases' => [
        'default' => [
            'driver' => env('DB_CONNECTION', 'sqlite'),
        ],
    ],

    /**
     * Each database instance must have an associated connection object.
     * Connections used to provide low-level functionality and wrap different
     * database drivers. To register a new connection you have to specify
     * the driver class and its connection options.
     */
    'drivers' => [
        'sqlite' => new Config\SQLiteDriverConfig(
            connection: new Config\SQLite\MemoryConnectionConfig(),
            queryCache: true
        ),
        'pgsql' => new Config\PostgresDriverConfig(
            connection: new Config\Postgres\TcpConnectionConfig(
                database: env('DB_DATABASE', 'spiral'),
                host: env('DB_HOST', '127.0.0.1'),
                port: (int)env('DB_PORT', 5432),
                user: env('DB_USERNAME', 'postgres'),
                password: env('DB_PASSWORD', ''),
            ),
            schema: 'public',
            queryCache: true,
        ),
        'mysql' => new Config\MySQLDriverConfig(
            connection: new Config\MySQL\TcpConnectionConfig(
                database: env('DB_DATABASE', 'spiral'),
                host: env('DB_HOST', '127.0.0.1'),
                port: (int)env('DB_PORT', 3307),
                user: env('DB_USERNAME', 'root'),
                password: env('DB_PASSWORD', ''),
            ),
            queryCache: true,
        ),
        // ...
    ],
];
