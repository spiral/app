<?php

declare(strict_types=1);

namespace Installer\Application\Web\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Module\Console\Generator\Skeleton as ConsoleSkeleton;
use Installer\Module\Exception\Generator\Skeleton as ExceptionSkeleton;

final class Skeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if (!$context->application->hasSkeleton()) {
            return;
        }

        $context->resource->copy('skeleton/app/src/Endpoint', 'app/src/Endpoint');
        $context->resource->copy('skeleton/tests', 'tests');

        (new ExceptionSkeleton())->process($context);
        (new ConsoleSkeleton())->process($context);

        \unlink($context->applicationRoot . 'tests/Feature/.gitignore');
    }
}
