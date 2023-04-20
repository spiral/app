<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Endpoint\Web\Middleware\LocaleSelector;
use App\Endpoint\Web\PageNotFoundController;
use Spiral\Bootloader\Http\RoutesBootloader as BaseRoutesBootloader;
use Spiral\Cookies\Middleware\CookiesMiddleware;
use Spiral\Csrf\Middleware\CsrfMiddleware;
use Spiral\Debug\StateCollector\HttpCollector;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Http\Middleware\JsonPayloadMiddleware;
use Spiral\Router\Bootloader\AnnotatedRoutesBootloader;
use Spiral\Router\Loader\Configurator\RoutingConfigurator;
use Spiral\Session\Middleware\SessionMiddleware;

final class RoutesBootloader extends BaseRoutesBootloader
{
    protected const DEPENDENCIES = [
        AnnotatedRoutesBootloader::class,
    ];

    protected function globalMiddleware(): array
    {
        return [
            LocaleSelector::class,
            ErrorHandlerMiddleware::class,
            JsonPayloadMiddleware::class,
            // HttpCollector::class,
        ];
    }

    protected function middlewareGroups(): array
    {
        return [
            'web' => [
                // CookiesMiddleware::class,
                // SessionMiddleware::class,
                // CsrfMiddleware::class,
                // new Autowire(AuthTransportMiddleware::class, ['transportName' => 'cookie'])
            ],
            'api' => [
                // new Autowire(AuthTransportMiddleware::class, ['transportName' => 'header'])
            ],
        ];
    }

    protected function defineRoutes(RoutingConfigurator $routes): void
    {
        // Fallback route if no other route matched
        // Will show 404 page
        $routes->default('/<path:.*>')
            ->controller(PageNotFoundController::class)
            ->defaults(['action' => '__invoke']);
    }
}
