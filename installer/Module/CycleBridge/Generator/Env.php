<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Env implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addGroup(
            values: [
                'SAFE_MIGRATIONS' => true,
            ],
            comment: 'Set to TRUE to disable confirmation in `migrate` commands',
            priority: 13,
        );

        $context->envConfigurator->addGroup(
            values: [
                'CYCLE_SCHEMA_CACHE' => false,
                'CYCLE_SCHEMA_WARMUP' => false,
            ],
            comment: 'Cycle Bridge (Don\'t forget to set `CYCLE_SCHEMA_CACHE` to `true` in production)',
            priority: 14,
        );

        $context->envConfigurator->addGroup(
            values: [
                'DB_CONNECTION' => 'sqlite',
            ],
            comment: 'Database',
            priority: 13,
        );
    }
}
