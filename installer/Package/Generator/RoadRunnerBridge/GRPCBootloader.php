<?php

declare(strict_types=1);

namespace Installer\Package\Generator\RoadRunnerBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Question\ApplicationSkeleton;
use Spiral\RoadRunnerBridge\Bootloader\LoggerBootloader;
use Spiral\RoadRunnerBridge\Bootloader\GRPCBootloader as Bootloader;

final class GRPCBootloader implements GeneratorInterface
{
    private const FILENAME = 'grpc.php';

    public function process(Context $context): void
    {
        $context->kernel->load->append(Bootloader::class, LoggerBootloader::class);

        $context->application->useRoadRunnerPlugin('grpc');

        $context->resource->copy('packages/grpc/config', 'app/config');

        $needSkeleton = $context->application->getOption(ApplicationSkeleton::class);

        $config = \file_get_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME,
        );

        $services = [];
        if ($needSkeleton) {
            $services[] = 'directory(\'root\') . \'proto/service.proto\'';
        }

        \file_put_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME,
            \str_replace(
                ':services:',
                \implode(", ", $services),
                $config,
            ),
        );
    }
}
