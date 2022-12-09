<?php

declare(strict_types=1);

namespace Installer\Package\Generator\LaravelSerializableClosure;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;

final class Env implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addGroup(
            values: [
                'DEFAULT_SERIALIZER_FORMAT' => 'closure',
            ],
            comment: 'Serializer',
            priority: 16
        );
    }
}
