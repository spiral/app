<?php

declare(strict_types=1);

namespace Installer\Module\TemporalBridge\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\TemporalBridge\Bootloader\PrototypeBootloader;
use Spiral\TemporalBridge\Bootloader\TemporalBridgeBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse('Spiral\TemporalBridge\Bootloader', 'TemporalBridge');

        $context->kernel->load->addGroup(
            bootloaders: [
                PrototypeBootloader::class,
                TemporalBridgeBootloader::class,
            ],
            comment: 'Temporal',
            priority: 16,
        );

        $context->application->useRoadRunnerPlugin('temporal');
    }
}
