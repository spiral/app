<?php

declare(strict_types=1);

namespace Installer\Package\Generator\CycleBridge;

use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\Cycle\Bootloader\AnnotatedBootloader;
use Spiral\Cycle\Bootloader\CommandBootloader;
use Spiral\Cycle\Bootloader\CycleOrmBootloader;
use Spiral\Cycle\Bootloader\DatabaseBootloader;
use Spiral\Cycle\Bootloader\MigrationsBootloader;
use Spiral\Cycle\Bootloader\SchemaBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse('Spiral\Cycle\Bootloader', 'CycleBridge');

        $context->kernel->loadAppend(DatabaseBootloader::class, GuardBootloader::class);
        $context->kernel->loadAppend(MigrationsBootloader::class, DatabaseBootloader::class);
        $context->kernel->loadAppend(SchemaBootloader::class, MigrationsBootloader::class);
        $context->kernel->loadAppend(CycleOrmBootloader::class, SchemaBootloader::class);
        $context->kernel->loadAppend(AnnotatedBootloader::class, CycleOrmBootloader::class);
        $context->kernel->loadAppend(CommandBootloader::class, AnnotatedBootloader::class);
    }
}
