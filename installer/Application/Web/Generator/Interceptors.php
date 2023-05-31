<?php

declare(strict_types=1);

namespace Installer\Application\Web\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Domain\GuardInterceptor;

final class Interceptors implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->domainInterceptors?->addUse(GuardInterceptor::class);
        $context->domainInterceptors?->addInterceptor(GuardInterceptor::class);
    }
}
