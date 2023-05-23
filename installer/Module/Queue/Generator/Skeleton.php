<?php

declare(strict_types=1);

namespace Installer\Module\Queue\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Skeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if ($context->application->hasSkeleton()) {
            $context->resource
                ->copy('app', 'app')
                ->copy('tests', 'tests');
        }
    }
}
