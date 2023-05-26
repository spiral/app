<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Cycle\Interceptor\CycleInterceptor;

final class Interceptors implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->domainInterceptors->addUse(CycleInterceptor::class);
        $context->domainInterceptors->addInterceptor(CycleInterceptor::class);
    }
}
