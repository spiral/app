<?php

declare(strict_types=1);

namespace Installer\Internal\Events;

final class ComposerUpdated
{
    public function __construct(
        public readonly string $path,
        public readonly array $data,
    ) {}
}
