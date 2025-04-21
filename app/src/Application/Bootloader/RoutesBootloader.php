<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Endpoint\Web\Middleware\LocaleSelector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Endpoint\Web\PageNotFoundController;
use Spiral\Bootloader\Http\RoutesBootloader as BaseRoutesBootloader;
use Spiral\Cookies\Middleware\CookiesMiddleware;
use Spiral\Csrf\Middleware\CsrfMiddleware;
use Spiral\Debug\Middleware\DumperMiddleware;
use Spiral\Debug\StateCollector\HttpCollector;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Http\Middleware\JsonPayloadMiddleware;
use Spiral\Router\Bootloader\AnnotatedRoutesBootloader;
use Spiral\Router\Loader\Configurator\RoutingConfigurator;
use Spiral\Session\Middleware\SessionMiddleware;

/**
 * A bootloader that configures the application's routes and middleware.
 *
 * @link https://spiral.dev/docs/http-routing
 */
final class RoutesBootloader extends BaseRoutesBootloader
{
    protected const DEPENDENCIES = [AnnotatedRoutesBootloader::class];

    #[\Override]
    protected function globalMiddleware(): array
    {
        return [
            LocaleSelector::class,
            ErrorHandlerMiddleware::class,
            DumperMiddleware::class,
            JsonPayloadMiddleware::class,
            HttpCollector::class,
        ];
    }

    #[\Override]
    protected function middlewareGroups(): array
    {
        return [
            'web' => [
                CookiesMiddleware::class,
                SessionMiddleware::class,
                CsrfMiddleware::class
            ],
        ];
    }

    #[\Override]
    protected function defineRoutes(RoutingConfigurator $routes): void
    {
        // Fallback route if no other route matched
        // Will show 404 page
        $routes->default('/<path:.*>')
            ->controller(PageNotFoundController::class)
            ->defaults(['action' => '__invoke']);
    }
}
