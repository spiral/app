<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Question\ApplicationSkeleton;

final class WebApplicationSkeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if ($context->application->getOption(ApplicationSkeleton::class) === true) {
            $context->resource->copy('applications/shared/app/src/Endpoint', 'app/src/Endpoint');
            $context->resource->copy('applications/shared/tests', 'tests');

            \unlink($context->applicationRoot . 'tests/Feature/.gitignore');
        }
    }
}
