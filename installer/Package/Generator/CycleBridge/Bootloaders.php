<?php

declare(strict_types=1);

namespace Installer\Package\Generator\CycleBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Bootloader\CommandBootloader as FrameworkCommands;
use Spiral\Cycle\Bootloader\AnnotatedBootloader;
use Spiral\Cycle\Bootloader\CommandBootloader;
use Spiral\Cycle\Bootloader\CycleOrmBootloader;
use Spiral\Cycle\Bootloader\DatabaseBootloader;
use Spiral\Cycle\Bootloader\MigrationsBootloader;
use Spiral\Cycle\Bootloader\SchemaBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse('Spiral\Cycle\Bootloader', 'CycleBridge');

        $context->kernel->load->addGroup(
            bootloaders: [
                DatabaseBootloader::class,
                MigrationsBootloader::class,
            ],
            comment: 'Databases',
            priority: 7
        );
        $context->kernel->load->addGroup(
            bootloaders: [
                SchemaBootloader::class,
                CycleOrmBootloader::class,
                AnnotatedBootloader::class,
            ],
            comment: 'ORM',
            priority: 8
        );
        $context->kernel->load->append(CommandBootloader::class, FrameworkCommands::class);
    }
}
