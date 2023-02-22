<?php

declare(strict_types=1);

namespace Installer\Package\Generator\CycleBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;

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
    }
}
