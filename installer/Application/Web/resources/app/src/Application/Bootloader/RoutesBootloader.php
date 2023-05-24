<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Bootloader\Http\RoutesBootloader as BaseRoutesBootloader;
use Spiral\Cookies\Middleware\CookiesMiddleware;
use Spiral\Csrf\Middleware\CsrfMiddleware;
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
    protected const DEPENDENCIES = [
        AnnotatedRoutesBootloader::class,
    ];

    protected function globalMiddleware(): array
    {
        return [];return [
            ErrorHandlerMiddleware::class,
            JsonPayloadMiddleware::class,
            HttpCollector::class,
        ];
    }

    protected function middlewareGroups(): array
    {
        return [];return [
            'web' => [
                CookiesMiddleware::class,
                SessionMiddleware::class,
                CsrfMiddleware::class
            ],
        ];
    }

    protected function defineRoutes(RoutingConfigurator $routes): void
    {
        // Fallback route if no other route matched
        // Will show 404 page
        // $routes->default('/<path:.*>')
        //    ->callable(function (ServerRequestInterface $r, ResponseInterface $response) {
        //        return $response->withStatus(404)->withBody('Not found');
        //    });
    }
}
