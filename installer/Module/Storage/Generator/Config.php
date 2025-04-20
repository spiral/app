<?php

declare(strict_types=1);

namespace Installer\Module\Storage\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Distribution\Bootloader\DistributionBootloader;
use Spiral\Storage\Bootloader\StorageBootloader;

final class Config implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(StorageBootloader::class);
        $context->kernel->addUse(DistributionBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                StorageBootloader::class,
                DistributionBootloader::class,
            ],
            comment: 'Storage',
            priority: 13,
        );

        $context->resource
            ->copy('config', 'app/config');

        $context->envConfigurator->addGroup(
            values: [
                'STORAGE_DEFAULT' => 'default',
            ],
            comment: 'Storage',
            priority: 8,
        );
    }
}
