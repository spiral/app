<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Module\CycleBridge\Package;
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

    public function getBootloaders(): array
    {
        return [
            AnnotatedBootloader::class,
            CommandBootloader::class,
            CycleOrmBootloader::class,
            DatabaseBootloader::class,
           // DataGridBootloader::class,
            MigrationsBootloader::class,
            ScaffolderBootloader::class,
            SchemaBootloader::class,
        ];
    }

    public function getEnvironmentVariables(): array
    {
        return [
            'SAFE_MIGRATIONS' => true,
            'CYCLE_SCHEMA_CACHE' => false,
            'CYCLE_SCHEMA_WARMUP' => false,
            'DB_CONNECTION' => 'sqlite'
        ];
    }
}
