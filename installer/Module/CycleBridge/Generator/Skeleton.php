<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge\Generator;

use App\Application\Bootloader\PersistenceBootloader;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Skeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if (!$context->application->hasSkeleton()) {
            return;
        }

        $context->resource
            ->copy('skeleton', 'app');

        $context->kernel->app->addGroup(
            bootloaders: [
                PersistenceBootloader::class,
            ],
            comment: 'User Domain',
            priority: 1,
        );
    }
}
