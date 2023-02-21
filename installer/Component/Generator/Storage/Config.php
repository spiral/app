<?php

declare(strict_types=1);

namespace Installer\Component\Generator\Storage;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Storage\Bootloader\StorageBootloader;
use Spiral\Distribution\Bootloader\DistributionBootloader;

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
            priority: 13
        );

        $context->resource->copy('components/storage/config', 'app/config');

        $context->envConfigurator->addGroup(
            values: [
                'STORAGE_DEFAULT' => 'default',
            ],
            comment: 'Storage',
            priority: 8
        );
    }
}