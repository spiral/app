<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge\Generator;

use App\Infrastructure\Cycle\ORMBootloader;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Module\DataGridBridge\Package as DataGrid;
use Spiral\Cycle\Bootloader\DataGridBootloader;
use Spiral\DataGrid\Bootloader\GridBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(ORMBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                ORMBootloader::class,
            ],
            comment: 'Databases and ORM',
            priority: 7,
        );

        if ($context->application->isPackageInstalled(new DataGrid())) {
            $context->kernel->addUse('Spiral\Cycle\Bootloader', 'CycleBridge');
            $context->kernel->load->append(DataGridBootloader::class, GridBootloader::class);
        }
    }
}
