<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\Metrics\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\RoadRunnerBridge\Bootloader\LoggerBootloader;
use Spiral\RoadRunnerBridge\Bootloader\MetricsBootloader as Bootloader;

final class MetricsBootloader implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->load->append(Bootloader::class, LoggerBootloader::class);

        $context->application->useRoadRunnerPlugin('metrics');
    }
}
