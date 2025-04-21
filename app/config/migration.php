<?php

declare(strict_types=1);

use Spiral\Boot\Environment\AppEnvironment;

/**
 * Migrations configuration.
 *
 * @link https://spiral.dev/docs/basics-orm#migrations
 */
return [
    /**
     * Directory to store migration files
     */
    'directory' => directory('app') . 'database/migrations/',

    /**
     * Table name to store information about migrations status (per database)
     */
    'table' => 'migrations',

    /**
     * When set to true no confirmation will be requested on migration run.
     */
    'safe' => env('SAFE_MIGRATIONS', spiral(AppEnvironment::class)->isProduction()),
];
