<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\RoadRunnerBridge\Common\Package;
use Spiral\RoadRunnerBridge\Bootloader\CacheBootloader;
use Spiral\RoadRunnerBridge\Bootloader\CommandBootloader;
use Spiral\RoadRunnerBridge\Bootloader\HttpBootloader;
use Spiral\RoadRunnerBridge\Bootloader\LoggerBootloader;
use Spiral\RoadRunnerBridge\Bootloader\QueueBootloader;
use Spiral\RoadRunnerBridge\Bootloader\ScaffolderBootloader;

final class RoadRunnerBridge extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        $plugins = $application->getRoadRunnerPlugins();

        $bootloaders = [
            LoggerBootloader::class,
            CommandBootloader::class,
            ScaffolderBootloader::class,
        ];

        if (\in_array('jobs', $plugins, true)) {
            $bootloaders[] = QueueBootloader::class;
        }

        if (\in_array('http', $plugins, true)) {
            $bootloaders[] = HttpBootloader::class;
        }

        if (\in_array('kv', $plugins, true)) {
            $bootloaders[] = CacheBootloader::class;
        }

        return $bootloaders;
    }

    public function getCopiedResources(ApplicationInterface $application): array
    {
        $resources = [];
        if ($application->isRoadRunnerPluginRequired('jobs')) {
            $resources['config/queue.php'] = 'app/config/queue.php';
        }
        if ($application->isRoadRunnerPluginRequired('kv')) {
            $resources['config/cache.php'] = 'app/config/cache.php';
        }

        return $resources;
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        $variables = [];
        if ($application->isRoadRunnerPluginRequired('jobs')) {
            $variables['QUEUE_CONNECTION'] = 'in-memory';
        }
        if ($application->isRoadRunnerPluginRequired('kv')) {
            $variables['CACHE_STORAGE'] = 'rr-local';
        }

        return $variables;
    }
}
