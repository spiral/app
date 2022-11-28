<?php

declare(strict_types=1);

namespace Installer\Package\Generator\Scheduler;

use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\Scheduler\Bootloader\SchedulerBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->loadAppend(SchedulerBootloader::class, GuardBootloader::class);
    }
}
