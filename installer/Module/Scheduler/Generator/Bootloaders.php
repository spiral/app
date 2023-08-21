<?php

declare(strict_types=1);

namespace Installer\Module\Scheduler\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
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
