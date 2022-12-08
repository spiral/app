<?php

declare(strict_types=1);

namespace Installer\Generator;

final class Message
{
    public function __construct(
        public readonly string $title,
        public readonly string $message,
        public readonly int $priority = 0
    ) {
    }
}
