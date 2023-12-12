<?php

declare(strict_types=1);

use Cycle\Database\Config;

/**
 * In this file, you may define all of your database connections, as well as specify which connection should be used
 * by default. Most of the configuration options within this file are driven by the values of your application's
 * environment variables.
 *
 * @link https://spiral.dev/docs/basics-orm#database
 */
return [
    /**
     * Log database queries through the use of the spiral/logger component.
     *
     * @link https://spiral.dev/docs/basics-orm#logging
     */
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
            queryCache: env('DB_QUERY_CACHE', true),
            options: [
                'logQueryParameters' => env('DB_LOG_QUERY_PARAMETERS', false),
                'withDatetimeMicroseconds' => env('DB_WITH_DATETIME_MICROSECONDS', false),
            ],
        ),
        'pgsql' => new Config\PostgresDriverConfig(
            connection: new Config\Postgres\TcpConnectionConfig(
                database: env('DB_DATABASE', 'spiral'),
                host: env('DB_HOST', '127.0.0.1'),
                port: (int)env('DB_PORT', 5432),
                user: env('DB_USERNAME', 'postgres'),
                password: env('DB_PASSWORD', ''),
            ),
            schema: env('DB_SCHEMA', 'public'),
            queryCache: env('DB_QUERY_CACHE', true),
            options: [
                'logQueryParameters' => env('DB_LOG_QUERY_PARAMETERS', false),
                'withDatetimeMicroseconds' => env('DB_WITH_DATETIME_MICROSECONDS', false),
            ],
        ),
        'mysql' => new Config\MySQLDriverConfig(
            connection: new Config\MySQL\TcpConnectionConfig(
                database: env('DB_DATABASE', 'spiral'),
                host: env('DB_HOST', '127.0.0.1'),
                port: (int)env('DB_PORT', 3307),
                user: env('DB_USERNAME', 'root'),
                password: env('DB_PASSWORD', ''),
            ),
            queryCache: env('DB_QUERY_CACHE', true),
            options: [
                'logQueryParameters' => env('DB_LOG_QUERY_PARAMETERS', false),
                'withDatetimeMicroseconds' => env('DB_WITH_DATETIME_MICROSECONDS', false),
            ],
        ),
        // ...
    ],
];
