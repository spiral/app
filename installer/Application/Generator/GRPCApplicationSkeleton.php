<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use Installer\Component\Generator\Console\Skeleton as ConsoleSkeleton;
use Installer\Component\Generator\Exception\Skeleton as ExceptionSkeleton;
use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Question\ApplicationSkeleton;

final class GRPCApplicationSkeleton implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if ($context->application->getOption(ApplicationSkeleton::class) === true) {
            (new ExceptionSkeleton())->process($context);
            (new ConsoleSkeleton())->process($context);

            \unlink($context->applicationRoot . 'tests/Feature/.gitignore');
        }
    }
}
