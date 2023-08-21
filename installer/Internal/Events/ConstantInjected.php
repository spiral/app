<?php

declare(strict_types=1);

namespace Installer\Internal\Events;

use Installer\Internal\Generator\Bootloader\Constant;

final class ConstantInjected
{
    public function __construct(
        public readonly string $class,
        public readonly Constant $constant
    ) {
    }
}
