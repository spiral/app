<?php

declare(strict_types=1);

namespace Installer\Package\Generator\RoadRunnerBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Question\ApplicationSkeleton;
use GRPC\Bootloader\ServiceBootloader;

final class GRPCSkeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if ($context->application->getOption(ApplicationSkeleton::class) !== true) {
            return;
        }

        $context->resource->copy('packages/grpc/proto', 'proto');
        $context->resource->copy('packages/grpc/generated', 'generated');
        $context->resource->copy('packages/grpc/app', 'app');

        $context->kernel->addUse(ServiceBootloader::class);
        $context->kernel->app->append(ServiceBootloader::class);

        $context->envConfigurator->addGroup([
            'PING_SERVICE_HOST' => '127.0.0.1:9001',
        ], 'GRPC services');
    }
}
