<?php

declare(strict_types=1);

namespace Installer\Package\Generator\SentryBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;

final class Env implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addGroup(
            values: [
                'SENTRY_DSN' => null,
            ],
            comment: 'Sentry',
            priority: 14
        );
    }
}
