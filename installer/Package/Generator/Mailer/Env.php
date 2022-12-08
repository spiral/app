<?php

declare(strict_types=1);

namespace Installer\Package\Generator\Mailer;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;

final class Env implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addGroup(
            values: [
                'MAILER_DSN' => null,
                'MAILER_PIPELINE' => 'local',
                'MAILER_FROM' => '"Spiral <sendit@local.host>"',
            ],
            comment: 'Mailer',
            priority: 9
        );
    }
}
