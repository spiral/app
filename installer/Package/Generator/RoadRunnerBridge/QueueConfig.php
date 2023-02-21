<?php

declare(strict_types=1);

namespace Installer\Package\Generator\RoadRunnerBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;

final class QueueConfig implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if (\in_array('jobs', $context->application->getRoadRunnerPlugins(), true)) {
            $context->resource->copy('packages/roadrunner/config/queue.php', 'app/config/queue.php');
        }
    }
}
