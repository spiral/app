<?php

declare(strict_types=1);

namespace Installer\Module\Serializers\SymfonySerializer\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Env implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->envConfigurator->addGroup(
            values: [
                'DEFAULT_SERIALIZER_FORMAT' => 'json # csv, xml, yaml',
            ],
            comment: 'Serializer',
            priority: 17,
        );
    }
}
