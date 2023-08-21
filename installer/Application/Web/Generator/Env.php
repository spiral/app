<?php

declare(strict_types=1);

namespace Installer\Application\Web\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Env implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addGroup(
            values: [
                'SESSION_LIFETIME' => 86400,
                'SESSION_COOKIE' => 'sid',
            ],
            comment: 'Session',
            priority: 10
        );

        $context->envConfigurator->addGroup(
            values: [
                'AUTH_TOKEN_TRANSPORT' => 'cookie',
                'AUTH_TOKEN_STORAGE' => 'session',
            ],
            comment: 'Authorization',
            priority: 11
        );
    }
}
