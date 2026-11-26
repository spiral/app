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
            'default # Use "roadrunner" channel for the RoadRunner logger or "remote" for JSON records over TCP to MONOLOG_REMOTE_URL',
        );
    }
}
