<?php

declare(strict_types=1);

namespace Installer\Internal\Events;

final class DeleteEvent
{
    public function __construct(
        public readonly string $path
    ) {
    }
}
