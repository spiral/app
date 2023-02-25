<?php

declare(strict_types=1);

namespace Installer\Component\Generator\Exception;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Question\ApplicationSkeleton;

final class Skeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if ($context->application->getOption(ApplicationSkeleton::class) === true) {
            $context->resource->copy('components/exceptions/app.php', 'app.php');
            $context->resource->copy('components/exceptions/app/src', 'app/src');
        }
    }
}
