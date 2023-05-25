<?php

declare(strict_types=1);

namespace Installer\Application\Web\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Skeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if (!$context->application->hasSkeleton()) {
            return;
        }

        $context->resource->copy(':web:/skeleton', '');

        \unlink($context->applicationRoot . 'tests/Feature/.gitignore');
    }
}
