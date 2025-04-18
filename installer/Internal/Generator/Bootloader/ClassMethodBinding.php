<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Bootloader;

use Spiral\Reactor\Partial\PhpNamespace;

final class ClassMethodBinding extends Value
{
    public function __construct(
        public readonly string $alias,
        public readonly string $methodName,
    ) {}

    public function render(PhpNamespace $namespace): string
    {
        $namespace->addUse($this->alias);

        return \implode(' => ', [
            $namespace->simplifyName($this->alias) . '::class',
            \sprintf("[self::class, '%s']", $this->methodName),
        ]);
    }
}
