<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\GRPC\Generator;

use GRPC\Bootloader\ServiceBootloader;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Skeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if (!$context->application->hasSkeleton()) {
            return;
        }

        $context->resource
            ->copy('grpc/proto', 'proto')
            ->copy('grpc/generated', 'generated')
            ->copy('grpc/app', 'app');

        $context->kernel->addUse(ServiceBootloader::class);
        $context->kernel->app->append(ServiceBootloader::class);

        $context->envConfigurator->addGroup([
            'PING_SERVICE_HOST' => '127.0.0.1:9001',
        ], 'GRPC services');
    }
}
