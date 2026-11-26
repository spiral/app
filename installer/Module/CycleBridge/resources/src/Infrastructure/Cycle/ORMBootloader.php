<?php

declare(strict_types=1);

namespace App\Infrastructure\Cycle;

use Cycle\Transaction\Bridge\Spiral\Bootloader\TransactionBootloader;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Cycle\Bootloader as CycleBridge;

/**
 * Databases, migrations and Cycle ORM with entity behaviors and transactions.
 *
 * @link https://spiral.dev/docs/basics-orm
 */
final class ORMBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        // Databases
        CycleBridge\DatabaseBootloader::class,
        CycleBridge\MigrationsBootloader::class,

        // ORM
        CycleBridge\SchemaBootloader::class,
        CycleBridge\CycleOrmBootloader::class,
        CycleBridge\AnnotatedBootloader::class,
        CycleBridge\EntityBehaviorBootloader::class,
        TransactionBootloader::class,

        // Console commands
        CycleBridge\CommandBootloader::class,
        CycleBridge\ScaffolderBootloader::class,
    ];
}
