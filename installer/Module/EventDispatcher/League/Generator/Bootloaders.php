<?php

declare(strict_types=1);

namespace Installer\Module\EventDispatcher\League\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Events\Bootloader\EventsBootloader;
use Spiral\League\Event\Bootloader\EventBootloader;

final class Bootloaders implements GeneratorInterface
{
    #[\Override]
    public function process(Context $context): void
    {
        $context->kernel
            ->addUse(EventsBootloader::class)
            ->addUse(EventBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                EventsBootloader::class,
                EventBootloader::class,
            ],
            comment: 'Event Dispatcher',
            priority: 8,
        );
    }
}
