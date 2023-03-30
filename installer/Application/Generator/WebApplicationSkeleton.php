<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use Installer\Component\Generator\Console\Skeleton as ConsoleSkeleton;
use Installer\Component\Generator\Exception\Skeleton as ExceptionSkeleton;
use Installer\Component\Generator\Translator\Skeleton as TranslatorSkeleton;
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

            (new ExceptionSkeleton())->process($context);
            (new ConsoleSkeleton())->process($context);
            (new TranslatorSkeleton())->process($context);

            \unlink($context->applicationRoot . 'tests/Feature/.gitignore');
        }
    }
}
