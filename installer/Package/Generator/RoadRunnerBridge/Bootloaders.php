<?php

declare(strict_types=1);

namespace Installer\Package\Generator\RoadRunnerBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Bootloader\CommandBootloader as FrameworkCommand;
use Spiral\RoadRunnerBridge\Bootloader\CacheBootloader;
use Spiral\RoadRunnerBridge\Bootloader\CommandBootloader;
use Spiral\RoadRunnerBridge\Bootloader\HttpBootloader;
use Spiral\RoadRunnerBridge\Bootloader\LoggerBootloader;
use Spiral\RoadRunnerBridge\Bootloader\QueueBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse('Spiral\RoadRunnerBridge\Bootloader', 'RoadRunnerBridge');

        $plugins = $context->application->getRoadRunnerPlugins();

        $bootloaders = [
            LoggerBootloader::class,
        ];

        if (\in_array('jobs', $plugins, true)) {
            $bootloaders[] = QueueBootloader::class;
        }

        if (\in_array('http', $plugins, true)) {
            $bootloaders[] = HttpBootloader::class;
        }

        if (\in_array('kv', $plugins, true)) {
            $bootloaders[] = CacheBootloader::class;
        }

        $context->kernel->load->addGroup(
            bootloaders: $bootloaders,
            comment: 'RoadRunner',
            priority: 3,
        );

        $context->kernel->load->append(CommandBootloader::class, FrameworkCommand::class);
    }
}
