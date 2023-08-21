<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Storage\Generator\Config;
use Spiral\Distribution\Bootloader\DistributionBootloader;
use Spiral\Storage\Bootloader\StorageBootloader;

final class Storage extends AbstractModule
{
    public function getGenerators(ApplicationInterface $application): array
    {
        return [
            Config::class,
        ];
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            DistributionBootloader::class,
            StorageBootloader::class,
        ];
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        return [
            'DEFAULT_SERIALIZER_FORMAT' => 'json # csv, xml, yaml',
        ];
    }
}
