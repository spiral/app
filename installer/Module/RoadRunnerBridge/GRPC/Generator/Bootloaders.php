<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\GRPC\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\RoadRunnerBridge\Bootloader\GRPCBootloader;
use Spiral\RoadRunnerBridge\Bootloader\LoggerBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->load->append(GRPCBootloader::class, LoggerBootloader::class);

        $context->application->useRoadRunnerPlugin('grpc');
    }
}
