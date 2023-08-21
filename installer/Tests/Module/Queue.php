<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Queue\Generator\Config;
use Installer\Module\Queue\Generator\Skeleton;
use Installer\Module\RoadRunnerBridge\Common\Package;
use Spiral\Queue\Bootloader\QueueBootloader;

final class Queue extends AbstractModule
{
    public function getGenerators(ApplicationInterface $application): array
    {
        return [
            Config::class,
            Skeleton::class,
        ];
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            QueueBootloader::class,
        ];
    }

    public function getCopiedResources(ApplicationInterface $application): array
    {
        return [
            'config' => 'app/config',
        ];
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        if ($application->isPackageInstalled(new Package())) {
            return [];
        }

        return [
            'QUEUE_CONNECTION' => 'sync',
        ];
    }
}
