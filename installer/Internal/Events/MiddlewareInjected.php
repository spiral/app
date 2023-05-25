<?php

declare(strict_types=1);

namespace Installer\Internal\Events;

use Installer\Internal\Generator\Kernel\ClassListGroup;

final class MiddlewareInjected
{
    public function __construct(
        public readonly string $class,
        public readonly string $group,
        public readonly ClassListGroup $middleware
    ) {
    }
}
