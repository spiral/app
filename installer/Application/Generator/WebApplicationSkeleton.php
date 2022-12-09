<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use App\Module\EmptyModule\Application\Bootloader;
use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Question\ApplicationSkeleton;

final class WebApplicationSkeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if ($context->application->getOption(ApplicationSkeleton::class) === true) {
            $context->resource->copy('applications/shared/app/src/Api', 'app/src/Api');
            $context->resource->copy('applications/shared/app/src/Module', 'app/src/Module');
            $context->resource->copy('applications/shared/tests', 'tests');

            \unlink($context->applicationRoot . 'tests/Feature/.gitignore');

            $context->kernel->app->addGroup(
                bootloaders: [Bootloader::class],
                comment: 'Modules'
            );
        }
    }
}
