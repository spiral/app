<?php

declare(strict_types=1);

namespace Installer\Module\DataGridBridge\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\DataGrid\Bootloader\GridBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(GridBootloader::class);

        $context->kernel->load
            ->addGroup(
                bootloaders: [
                    GridBootloader::class,
                ],
                comment: 'Data Grid',
                priority: 16
            );
    }
}
