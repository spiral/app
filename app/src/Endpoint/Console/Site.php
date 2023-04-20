<?php

declare(strict_types=1);

namespace App\Endpoint\Console;

final class Site
{
    public function __construct(
        public readonly string $url,
    ) {
    }
}
