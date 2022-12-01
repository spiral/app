<?php

declare(strict_types=1);

namespace Installer\Package\Generator\Scheduler;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Scheduler\Bootloader\SchedulerBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(SchedulerBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [SchedulerBootloader::class],
            comment: 'Scheduler',
            priority: 9
        );
    }
}
