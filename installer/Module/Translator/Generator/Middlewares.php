<?php

declare(strict_types=1);

namespace Installer\Module\Translator\Generator;

use App\Endpoint\Web\Middleware\LocaleSelector;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;

final class Middlewares implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->routesBootloader?->addUse(LocaleSelector::class);

        $context->routesBootloader?->addGlobalMiddleware(
            middleware: [LocaleSelector::class],
            beforeMiddleware: ErrorHandlerMiddleware::class
        );
    }
}
