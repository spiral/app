<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\Common\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Env implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addValue(
            'MONOLOG_DEFAULT_CHANNEL',
            'default # Use "roadrunner" channel if you want to use RoadRunner logger',
        );
    }
}
