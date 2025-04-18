<?php

declare(strict_types=1);

namespace Installer\Module\Dumper\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Debug\Bootloader\DumperBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(DumperBootloader::class);

        $context->kernel->system->addGroup(
            bootloaders: [
                DumperBootloader::class,
            ],
        );
    }
}
