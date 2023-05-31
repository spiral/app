<?php

declare(strict_types=1);

namespace Installer\Module\SentryBridge\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Env implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addGroup(
            values: [
                'SENTRY_DSN' => null,
            ],
            comment: 'Sentry',
            priority: 15
        );
    }
}