<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\GRPC\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Config implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if ($context->application->hasSkeleton()) {
            $context->resource
                ->copy('config/grpc_services.php', 'app/config/grpc.php');
        } else {
            $context->resource
                ->copy('config/grpc.php', 'app/config/grpc.php');
        }
    }
}
