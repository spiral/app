<?php

declare(strict_types=1);

namespace Installer\Module\DataGridBridge\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\DataGrid\Interceptor\GridInterceptor;

final class Interceptors implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->domainInterceptors->addUse(GridInterceptor::class);
        $context->domainInterceptors->addInterceptor(GridInterceptor::class);
    }
}
