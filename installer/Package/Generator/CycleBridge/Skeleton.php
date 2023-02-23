<?php

declare(strict_types=1);

namespace Installer\Package\Generator\CycleBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Question\ApplicationSkeleton;
use App\Application\Bootloader\PersistenceBootloader;

final class Skeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if ($context->application->getOption(ApplicationSkeleton::class) === true) {
            $context->resource->copy('packages/cycle/skeleton', 'app');

            $context->kernel->app->addGroup(
                bootloaders: [
                    PersistenceBootloader::class,
                ],
                comment: 'User Domain',
                priority: 1,
            );
        }
    }
}
