<?php

declare(strict_types=1);

use Spiral\Boot\Environment\AppEnvironment;

return [
    /**
     * Directory to store migration files.
     */
    'directory' => directory('app').'migrations/',

    /**
     * Table name to store information about migrations status (per database).
     */
    'table' => 'migrations',

    /**
     * When set to true no confirmation will be requested on migration run.
     */
    'safe' => spiral(AppEnvironment::class)->isProduction(),
];
