<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\GRPC\Generator;

use Installer\Application\ApplicationSkeleton;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\RoadRunnerBridge\Bootloader\GRPCBootloader as Bootloader;
use Spiral\RoadRunnerBridge\Bootloader\LoggerBootloader;

final class GRPCBootloader implements GeneratorInterface
{
    private const FILENAME = 'grpc.php';

    public function process(Context $context): void
    {
        $context->kernel->load->append(Bootloader::class, LoggerBootloader::class);

        $context->application->useRoadRunnerPlugin('grpc');

        $context->resource
            ->copy('config/grpc.php', 'app/config/grpc.php');

        $config = \file_get_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME,
        );

        $services = [];
        if ($context->application->hasSkeleton()) {
            $services[] = 'directory(\'root\') . \'proto/service.proto\'';
        }

        \file_put_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME,
            \str_replace(
                ':services:',
                \implode(', ', $services),
                $config,
            ),
        );
    }
}
