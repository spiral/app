<?php

declare(strict_types=1);

namespace Installer\Module\Cache\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Cache\Bootloader\CacheBootloader;

final class Config implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $this->injectBootloader($context);

        $context->application->useRoadRunnerPlugin('kv');
        $this->generateConfig($context);
    }

    public function injectBootloader(Context $context): void
    {
        $context->kernel->addUse(CacheBootloader::class);
        $context->kernel->load->addGroup(
            bootloaders: [
                CacheBootloader::class,
            ],
            comment: 'Cache',
            priority: 13
        );
    }

    public function generateConfig(Context $context): void
    {
        $context->resource
            ->copy('config', 'app/config');

        $context->envConfigurator->addGroup(
            values: [
                'CACHE_STORAGE' => 'local',
            ],
            comment: 'Cache',
            priority: 7
        );
    }
}
