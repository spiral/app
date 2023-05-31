<?php

declare(strict_types=1);

namespace Installer\Module\Validators\Laravel\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Filter\ValidationHandlerMiddleware;

final class Middlewares implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->routesBootloader?->addMiddlewareGroup('web', [ValidationHandlerMiddleware::class]);
    }
}
