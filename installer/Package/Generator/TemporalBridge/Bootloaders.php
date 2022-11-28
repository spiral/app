<?php

declare(strict_types=1);

namespace Installer\Package\Generator\TemporalBridge;

use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\TemporalBridge\Bootloader\PrototypeBootloader;
use Spiral\TemporalBridge\Bootloader\TemporalBridgeBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(PrototypeBootloader::class);
        $context->kernel->addUse(TemporalBridgeBootloader::class);

        $context->kernel->loadAppend(PrototypeBootloader::class, GuardBootloader::class);
        $context->kernel->loadAppend(TemporalBridgeBootloader::class, PrototypeBootloader::class);
    }
}
