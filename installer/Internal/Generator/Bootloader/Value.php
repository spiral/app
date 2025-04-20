<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Bootloader;

use Spiral\Reactor\Partial\PhpNamespace;

abstract class Value
{
    public function __construct(
        public readonly string $alias,
        public readonly string $value,
    ) {}

    abstract public function render(PhpNamespace $namespace): string;
}
