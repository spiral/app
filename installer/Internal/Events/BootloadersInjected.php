<?php

declare(strict_types=1);

namespace Installer\Internal\Events;

use Installer\Internal\Generator\Kernel\BootloaderPlaces;
use Installer\Internal\Generator\Kernel\ClassListGroup;

final class BootloadersInjected
{
    /**
     * @param ClassListGroup[] $group
     */
    public function __construct(
        public readonly string $class,
        public readonly BootloaderPlaces $place,
        public readonly array $group,
    ) {
    }
}
