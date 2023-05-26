<?php

declare(strict_types=1);

namespace Installer\Module\Exception\Generator;

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
            ->copy(\dirname(__DIR__) . '/resources/app.php', 'app.php')
            ->copy(\dirname(__DIR__) . '/resources/app/src', 'app/src');
    }
}
