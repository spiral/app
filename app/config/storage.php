<?php

declare(strict_types=1);

use Spiral\Storage\Storage;

return [
    'default' => env('STORAGE_DEFAULT', Storage::DEFAULT_STORAGE),
    'servers' => [],
    'buckets' => [],
];
