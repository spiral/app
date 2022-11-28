<?php

declare(strict_types=1);

namespace Installer\Package\Generator\RoadRunnerBridge;

use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Spiral\Bootloader\CommandBootloader as FrameworkCommand;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\RoadRunnerBridge\Bootloader\CacheBootloader;
use Spiral\RoadRunnerBridge\Bootloader\CommandBootloader;
use Spiral\RoadRunnerBridge\Bootloader\HttpBootloader;
use Spiral\RoadRunnerBridge\Bootloader\QueueBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse('Spiral\RoadRunnerBridge\Bootloader', 'RoadRunnerBridge');

        $context->kernel->loadAppend(CacheBootloader::class, GuardBootloader::class);
        $context->kernel->loadAppend(HttpBootloader::class, CacheBootloader::class);
        $context->kernel->loadAppend(QueueBootloader::class, HttpBootloader::class);
        $context->kernel->loadAppend(CommandBootloader::class, FrameworkCommand::class);
    }
}
