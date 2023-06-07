<?php

declare(strict_types=1);

namespace Installer\Application\GRPC\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;

final class Skeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if (!$context->application->hasSkeleton()) {
            return;
        }

        if (\file_exists($context->applicationRoot . 'tests/Feature/.gitignore')) {
            \unlink($context->applicationRoot . 'tests/Feature/.gitignore');
        }
    }
}
