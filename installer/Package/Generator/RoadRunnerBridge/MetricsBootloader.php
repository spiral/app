<?php

declare(strict_types=1);

namespace Installer\Package\Generator\RoadRunnerBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\RoadRunnerBridge\Bootloader\CacheBootloader;
use Spiral\RoadRunnerBridge\Bootloader\MetricsBootloader as Bootloader;

final class MetricsBootloader implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->load->append(Bootloader::class, CacheBootloader::class);

        $context->application->useRoadRunnerPlugin('metrics');
    }
}
