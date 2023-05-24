<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Bootloader;

use Spiral\Reactor\Partial\PhpNamespace;

final class ClassName extends Value
{
    public function __construct(
        public readonly string $class
    ) {
    }

    public function render(PhpNamespace $namespace): string
    {
        $namespace->addUse($this->class);

        return $namespace->simplifyName($this->class) . '::class';
    }
}
