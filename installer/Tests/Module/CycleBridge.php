<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\CycleBridge\Package;
use Installer\Module\DataGridBridge\Package as DataGridPackage;
use Spiral\Cycle\Bootloader\AnnotatedBootloader;
use Spiral\Cycle\Bootloader\CommandBootloader;
use Spiral\Cycle\Bootloader\CycleOrmBootloader;
use Spiral\Cycle\Bootloader\DatabaseBootloader;
use Spiral\Cycle\Bootloader\DataGridBootloader;
use Spiral\Cycle\Bootloader\MigrationsBootloader;
use Spiral\Cycle\Bootloader\ScaffolderBootloader;
use Spiral\Cycle\Bootloader\SchemaBootloader;

final class CycleBridge extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        $bootloaders = [
            AnnotatedBootloader::class,
            CommandBootloader::class,
            CycleOrmBootloader::class,
            DatabaseBootloader::class,
            MigrationsBootloader::class,
            ScaffolderBootloader::class,
            SchemaBootloader::class,
        ];

        return $application->isPackageInstalled(new DataGridPackage())
            ? $bootloaders + [DataGridBootloader::class]
            : $bootloaders;
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        return [
            'SAFE_MIGRATIONS' => true,
            'CYCLE_SCHEMA_CACHE' => false,
            'CYCLE_SCHEMA_WARMUP' => false,
            'DB_CONNECTION' => 'sqlite'
        ];
    }
}
