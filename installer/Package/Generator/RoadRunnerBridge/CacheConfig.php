<?php

declare(strict_types=1);

namespace Installer\Package\Generator\RoadRunnerBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\RoadRunnerBridge\Bootloader\CacheBootloader;

final class CacheConfig implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if (\in_array('kv', $context->application->getRoadRunnerPlugins(), true)) {
            $context->resource->copy('packages/roadrunner/config/cache.php', 'app/config/cache.php');
        }
    }
}
