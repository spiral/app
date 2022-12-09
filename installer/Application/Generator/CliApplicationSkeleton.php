<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use App\Module\EmptyModule\Application\Bootloader;
use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Question\ApplicationSkeleton;

final class CliApplicationSkeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if ($context->application->getOption(ApplicationSkeleton::class) === true) {
            $context->resource->copy('applications/shared/app/src/Api/Cli', 'app/src/Api/Cli');
            $context->resource->copy('applications/shared/app/src/Api/Job', 'app/src/Api/Job');
            $context->resource->copy('applications/shared/app/src/Api/Rpc', 'app/src/Api/Rpc');
            $context->resource->copy('applications/shared/app/src/Module', 'app/src/Module');
            $context->resource->copy('applications/shared/tests/Feature/Job', 'tests/Feature/Job');

            \unlink($context->applicationRoot . 'tests/Feature/.gitignore');

            $context->kernel->app->addGroup(
                bootloaders: [Bootloader::class],
                comment: 'Modules'
            );
        }
    }
}
