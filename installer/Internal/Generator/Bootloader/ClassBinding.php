<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Bootloader;

use Spiral\Reactor\Partial\PhpNamespace;

final class ClassBinding extends Value
{
    public function render(PhpNamespace $namespace): string
    {
        $namespace->addUse($this->alias);
        $namespace->addUse($this->value);

        return \implode(' => ', [
            $namespace->simplifyName($this->alias) . '::class',
            $namespace->simplifyName($this->value) . '::class'
        ]);
    }
}
