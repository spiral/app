<?php

declare(strict_types=1);

namespace Installer\Package\Generator\LeagueEvent;

use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Spiral\Bootloader\CommandBootloader;
use Spiral\Events\Bootloader\EventsBootloader;
use Spiral\League\Event\Bootloader\EventBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->loadAppend(EventsBootloader::class, CommandBootloader::class);
        $context->kernel->loadAppend(EventBootloader::class, EventsBootloader::class);
    }
}
