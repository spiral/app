<?php

declare(strict_types=1);

namespace Installer\Internal\Events;

final class ReadmeGenerated
{
    public function __construct(
        public readonly string $path,
        public readonly string $content,
    ) {}
}
