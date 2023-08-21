<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\Common\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\RoadRunnerBridge\Bootloader\CacheBootloader;

final class CacheConfig implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if (!$context->application->isRoadRunnerPluginRequired('kv')) {
            return;
        }

        $context->envConfigurator
            ->addValue('CACHE_STORAGE', 'rr-local');

        $context->resource
            ->copy('config/cache.php', 'app/config/cache.php');
    }
}
