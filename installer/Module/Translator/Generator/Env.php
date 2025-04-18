<?php

declare(strict_types=1);

namespace Installer\Module\Translator\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Env implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addGroup(
            values: [
                'LOCALE' => 'en',
            ],
            comment: 'Internationalization',
            priority: 19,
        );
    }
}
