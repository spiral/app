<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Cache\Generator\Config;
use Spiral\Cache\Bootloader\CacheBootloader;

final class Cache extends AbstractModule
{
    public function getGenerators(ApplicationInterface $application): array
    {
        return [
            Config::class,
        ];
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            CacheBootloader::class,
        ];
    }

    public function getCopiedResources(ApplicationInterface $application): array
    {
        return [
            'config' => 'app/config',
        ];
    }

    public function getResourcesPath(): ?string
    {
        return \dirname(__DIR__, 2) . '/Module/Cache/resources/';
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        return [
            'CACHE_STORAGE' => 'local',
        ];
    }
}
