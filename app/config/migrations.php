<?php

declare(strict_types=1);

return [
    // directory to store migration files
    'directory' => directory('application').'migrations/',

    // Table name to store information about migrations status (per database)
    'table' => 'migrations',

    // When set to true no confirmation will be requested on migration run.
    'safe' => env('SPIRAL_ENV') == 'develop',
];
