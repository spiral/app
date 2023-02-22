<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Question\ApplicationSkeleton;
use Installer\Question\Queue;

final class CliApplicationSkeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if ($context->application->getOption(ApplicationSkeleton::class) === true) {
            $context->resource->copy(
                'applications/shared/app/src/Endpoint/Console',
                'app/src/Endpoint/Console',
            );

            if ($context->application->getOption(Queue::class) === true) {
                $context->resource->copy(
                    'applications/shared/app/src/Endpoint/Job',
                    'app/src/Endpoint/Job',
                );
            }

            $context->resource->copy('components/exceptions/app.php', 'app.php');
            $context->resource->copy('components/exceptions/app/src', 'app/src');

            $context->resource->copy('applications/shared/tests/Feature/Job', 'tests/Feature/Job');

            \unlink($context->applicationRoot . 'tests/Feature/.gitignore');
        }
    }
}
