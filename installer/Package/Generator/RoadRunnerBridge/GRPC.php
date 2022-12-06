<?php

declare(strict_types=1);

namespace Installer\Package\Generator\RoadRunnerBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\RoadRunnerBridge\Bootloader\CacheBootloader;
use Spiral\RoadRunnerBridge\Bootloader\GRPCBootloader;

final class GRPC implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->load->append(GRPCBootloader::class, CacheBootloader::class);

        $context->application->useRoadRunnerPlugin('grpc');

        $context->resource->copy('packages/grpc/config', 'app/config');
    }
}
