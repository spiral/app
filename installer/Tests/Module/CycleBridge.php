<?php

declare(strict_types=1);

namespace Tests\Module;

use App\Infrastructure\Cycle\ORMBootloader;
use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\CycleBridge\Package;
use Installer\Module\DataGridBridge\Package as DataGridPackage;
use Spiral\Cycle\Bootloader\DataGridBootloader;

final class CycleBridge extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        $bootloaders = [ORMBootloader::class];

        return $application->isPackageInstalled(new DataGridPackage())
            ? [...$bootloaders, DataGridBootloader::class]
            : $bootloaders;
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        return [
            'SAFE_MIGRATIONS' => true,
            'CYCLE_SCHEMA_CACHE' => false,
            'CYCLE_SCHEMA_WARMUP' => false,
            'DB_CONNECTION' => 'sqlite',
            'DB_LOG_QUERY_PARAMETERS' => false,
            'DB_WITH_DATETIME_MICROSECONDS' => false,
        ];
    }
}
