<?php

declare(strict_types=1);

namespace Installer\Internal\Events;

final class DloadConfigGenerated
{
    /**
     * @param array<non-empty-string, non-empty-string> $software Software alias => version constraint
     */
    public function __construct(
        public readonly string $path,
        public readonly array $software,
    ) {}
}
