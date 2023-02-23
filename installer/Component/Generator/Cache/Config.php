<?php

declare(strict_types=1);

namespace Installer\Component\Generator\Cache;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Cache\Bootloader\CacheBootloader;

final class Config implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(CacheBootloader::class);
        $context->kernel->load->addGroup(
            bootloaders: [
                CacheBootloader::class,
            ],
            comment: 'Cache',
            priority: 13
        );

        $context->application->useRoadRunnerPlugin('kv');

        $context->resource->copy('components/cache/config', 'app/config');

        $context->envConfigurator->addGroup(
            values: [
                'CACHE_STORAGE' => 'local',
            ],
            comment: 'Cache',
            priority: 7
        );
    }
}
