<?php

declare(strict_types=1);

namespace Installer\Module\Dumper\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Module\Http\Package;
use Spiral\Debug\Middleware\DumperMiddleware;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;

final class Middlewares implements GeneratorInterface
{
    public function process(Context $context): void
    {
        if (!$context->application->isPackageInstalled(new Package())) {
            return;
        }

        $context->routesBootloader?->addUse(DumperMiddleware::class);

        $context->routesBootloader?->addGlobalMiddleware(
            middleware: [DumperMiddleware::class],
            afterMiddleware: ErrorHandlerMiddleware::class
        );
    }
}
