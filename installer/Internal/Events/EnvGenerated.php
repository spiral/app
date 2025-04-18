<?php

declare(strict_types=1);

namespace Installer\Internal\Events;

use Installer\Internal\Generator\Env\EnvGroup;

final class EnvGenerated
{
    /**
     * @param EnvGroup[] $envs
     */
    public function __construct(
        public readonly string $path,
        public readonly array $envs,
    ) {}
}
