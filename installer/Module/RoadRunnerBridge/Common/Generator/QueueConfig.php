<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\Common\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class QueueConfig implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if (!$context->application->isRoadRunnerPluginRequired('jobs')) {
            return;
        }

        $context->envConfigurator
            ->addValue('QUEUE_CONNECTION', 'in-memory');

        $context->resource
            ->copy('config/queue.php', 'app/config/queue.php');
    }
}
