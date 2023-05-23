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
            ->copy('app.php', 'app.php')
            ->copy('app/src', 'app/src');
    }
}
