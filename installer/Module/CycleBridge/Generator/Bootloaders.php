<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Module\DataGridBridge\Package as DataGrid;
use Spiral\Bootloader\CommandBootloader as FrameworkCommands;
use Spiral\Cycle\Bootloader\AnnotatedBootloader;
use Spiral\Cycle\Bootloader\CommandBootloader;
use Spiral\Cycle\Bootloader\CycleOrmBootloader;
use Spiral\Cycle\Bootloader\DatabaseBootloader;
use Spiral\Cycle\Bootloader\DataGridBootloader;
use Spiral\Cycle\Bootloader\MigrationsBootloader;
use Spiral\Cycle\Bootloader\ScaffolderBootloader;
use Spiral\Cycle\Bootloader\SchemaBootloader;
use Spiral\DataGrid\Bootloader\GridBootloader;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader as FrameworkScaffolder;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse('Spiral\Cycle\Bootloader', 'CycleBridge');

        $context->kernel->load
            ->addGroup(
                bootloaders: [
                    DatabaseBootloader::class,
                    MigrationsBootloader::class,
                ],
                comment: 'Databases',
                priority: 7
            )
            ->addGroup(
                bootloaders: [
                    SchemaBootloader::class,
                    CycleOrmBootloader::class,
                    AnnotatedBootloader::class,
                ],
                comment: 'ORM',
                priority: 8
            )
            ->append(CommandBootloader::class, FrameworkCommands::class)
            ->append(ScaffolderBootloader::class, FrameworkScaffolder::class);

        if ($context->application->isPackageInstalled(new DataGrid())) {
            $context->kernel->load->append(DataGridBootloader::class, GridBootloader::class);
        }
    }
}
