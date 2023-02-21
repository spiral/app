<?php

declare(strict_types=1);

namespace Installer\Package\Generator\RoadRunnerBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;

final class Env implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addValue('QUEUE_CONNECTION', 'roadrunner');
        $context->envConfigurator->addValue('CACHE_STORAGE', 'rr-local');

        $context->envConfigurator->addGroup(
            values: [
                'MONOLOG_DEFAULT_CHANNEL' => 'roadrunner',
            ],
            comment: 'RoadRunner Logger',
            priority: 16,
        );
    }
}
