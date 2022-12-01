<?php

declare(strict_types=1);

namespace Installer\Package\Generator\TemporalBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\TemporalBridge\Bootloader\PrototypeBootloader;
use Spiral\TemporalBridge\Bootloader\TemporalBridgeBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(PrototypeBootloader::class);
        $context->kernel->addUse(TemporalBridgeBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                PrototypeBootloader::class,
                TemporalBridgeBootloader::class,
            ],
            comment: 'Temporal',
            priority: 13
        );

        $context->application->useRoadRunnerPlugin('temporal');
    }
}
