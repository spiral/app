<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;

final class WebApplicationEnv implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addGroup(
            values: [
                'SESSION_LIFETIME' => 86400,
                'SESSION_COOKIE' => 'sid',
            ],
            comment: 'Session',
            priority: 8
        );
    }
}
