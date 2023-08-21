<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\Common\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Readme\Block\FileBlock;
use Installer\Internal\Readme\Section;
use Spiral\Bootloader\CommandBootloader as FrameworkCommand;
use Spiral\RoadRunnerBridge\Bootloader\CacheBootloader;
use Spiral\RoadRunnerBridge\Bootloader\CommandBootloader;
use Spiral\RoadRunnerBridge\Bootloader\ScaffolderBootloader;
use Spiral\RoadRunnerBridge\Bootloader\HttpBootloader;
use Spiral\RoadRunnerBridge\Bootloader\LoggerBootloader;
use Spiral\RoadRunnerBridge\Bootloader\QueueBootloader;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader as FrameworkScaffolder;

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
            $context->readme[Section::Usage->value][] = new FileBlock(__DIR__ . '/../../readme/queue/usage.md');
        }

        if (\in_array('http', $plugins, true)) {
            $bootloaders[] = HttpBootloader::class;
            $context->readme[Section::Usage->value][] = new FileBlock(__DIR__ . '/../../readme/http/usage.md');
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
        $context->kernel->load->append(ScaffolderBootloader::class, FrameworkScaffolder::class);
    }
}
