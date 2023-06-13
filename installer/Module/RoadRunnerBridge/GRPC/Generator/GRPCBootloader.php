<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\GRPC\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\RoadRunnerBridge\Bootloader\GRPCBootloader as Bootloader;
use Spiral\RoadRunnerBridge\Bootloader\LoggerBootloader;

final class GRPCBootloader implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->load->append(Bootloader::class, LoggerBootloader::class);

        $context->application->useRoadRunnerPlugin('grpc');

        $context->resource
            ->copy('config/grpc.php', 'app/config/grpc.php');
    }
}
