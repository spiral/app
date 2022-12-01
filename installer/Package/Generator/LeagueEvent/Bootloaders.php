<?php

declare(strict_types=1);

namespace Installer\Package\Generator\LeagueEvent;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Events\Bootloader\EventsBootloader;
use Spiral\League\Event\Bootloader\EventBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(EventsBootloader::class);
        $context->kernel->addUse(EventBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                EventsBootloader::class,
                EventBootloader::class,
            ],
            comment: 'Event Dispatcher',
            priority: 8
        );
    }
}
