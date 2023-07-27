<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\RoadRunnerBridge\GRPC\Package;
use GRPC\Bootloader\ServiceBootloader;
use Spiral\RoadRunnerBridge\Bootloader\GRPCBootloader;

final class RoadRunnerGRPC extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        $bootloaders = [
            GRPCBootloader::class,
        ];

        return $application->hasSkeleton()
            ? $bootloaders + [ServiceBootloader::class]
            : $bootloaders;
    }

    public function getCopiedResources(ApplicationInterface $application): array
    {
        if (!$application->hasSkeleton()) {
            return ['config/grpc.php', 'app/config/grpc.php'];
        }

        return [
            'config/grpc_services.php' => 'app/config/grpc.php',
            'grpc/proto' => 'proto',
            'grpc/generated' => 'generated',
            'grpc/app' => 'app',
        ];
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        if (!$application->hasSkeleton()) {
            return [];
        }

        return ['PING_SERVICE_HOST' => '127.0.0.1:9001'];
    }
}
