<?php

declare(strict_types=1);

namespace Installer\Package\Generator\TemporalBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;

final class Env implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addGroup(
            values: [
                'TEMPORAL_ADDRESS' => '127.0.0.1:7233',
                'TEMPORAL_TASK_QUEUE' => 'default',
            ],
            comment: 'Temporal bridge configuration',
            priority: 15
        );
    }
}
