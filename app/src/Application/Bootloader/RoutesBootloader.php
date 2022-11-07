<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Application\Middleware\LocaleSelector;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Bootloader\Http\RoutesBootloader as BaseRoutesBootloader;
use Spiral\Cookies\Middleware\CookiesMiddleware;
use Spiral\Csrf\Middleware\CsrfMiddleware;
use Spiral\Debug\StateCollector\HttpCollector;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Http\Middleware\JsonPayloadMiddleware;
use Spiral\Router\Loader\Configurator\RoutingConfigurator;
use Spiral\Session\Middleware\SessionMiddleware;

final class RoutesBootloader extends BaseRoutesBootloader
{
    public function __construct(
        private readonly DirectoriesInterface $dirs
    ) {
    }

    protected function globalMiddleware(): array
    {
        return [
            LocaleSelector::class,
            ErrorHandlerMiddleware::class,
            JsonPayloadMiddleware::class,
            HttpCollector::class,
        ];
    }

    protected function middlewareGroups(): array
    {
        return [
            'web' => [
                CookiesMiddleware::class,
                SessionMiddleware::class,
                CsrfMiddleware::class,
                // new Autowire(AuthTransportMiddleware::class, ['transportName' => 'cookie'])
            ],
            'api' => [
                // new Autowire(AuthTransportMiddleware::class, ['transportName' => 'header'])
            ],
        ];
    }

    protected function defineRoutes(RoutingConfigurator $routes): void
    {
        $routes->import($this->dirs->get('app') . 'src/Api/Web/routes.php')->group('web');

        $routes->default('/[<controller>[/<action>]]')
            ->namespaced('App\\Api\\Web\\Controller')
            ->defaults([
                'controller' => 'home',
                'action' => 'index',
            ])
            ->middleware([
                // SomeMiddleware::class,
            ]);
    }
}
