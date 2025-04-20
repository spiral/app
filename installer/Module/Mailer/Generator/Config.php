<?php

declare(strict_types=1);

namespace Installer\Module\Mailer\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Config implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->resource->copy('config', 'app/config');

        $context->envConfigurator->addGroup(
            values: [
                'MAILER_DSN' => null,
                'MAILER_QUEUE' => 'local',
                'MAILER_QUEUE_CONNECTION' => null,
                'MAILER_FROM' => '"Spiral <sendit@local.host>"',
            ],
            comment: 'Mailer',
            priority: 12,
        );
    }
}
