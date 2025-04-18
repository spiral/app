<?php

declare(strict_types=1);

namespace Installer\Internal\Events;

final class PackageRegistered
{
    public function __construct(
        public readonly string $name,
        public readonly string $version,
        public readonly bool $isDev,
    ) {}
}
