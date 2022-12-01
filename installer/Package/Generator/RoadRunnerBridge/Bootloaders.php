<?php

declare(strict_types=1);

namespace Installer\Package\Generator\RoadRunnerBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Bootloader\CommandBootloader as FrameworkCommand;
use Spiral\RoadRunnerBridge\Bootloader\CacheBootloader;
use Spiral\RoadRunnerBridge\Bootloader\CommandBootloader;
use Spiral\RoadRunnerBridge\Bootloader\HttpBootloader;
use Spiral\RoadRunnerBridge\Bootloader\QueueBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse('Spiral\RoadRunnerBridge\Bootloader', 'RoadRunnerBridge');

        $context->kernel->load->addGroup(
            bootloaders: [
                CacheBootloader::class,
                HttpBootloader::class,
                QueueBootloader::class,
            ],
            comment: 'RoadRunner',
            priority: 3
        );

        $context->kernel->load->append(CommandBootloader::class, FrameworkCommand::class);

        $context->application->useRoadRunnerPlugin('http', 'jobs', 'kv');
    }
}
