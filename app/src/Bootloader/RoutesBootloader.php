<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace App\Bootloader;

use App\Controller\HomeController;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Router\Route;
use Spiral\Router\RouteInterface;
use Spiral\Router\RouterInterface;
use Spiral\Router\Target\Controller;
use Spiral\Router\Target\Namespaced;

class RoutesBootloader extends Bootloader
{
    /**
     * @param RouterInterface $router
     */
    public function boot(RouterInterface $router): void
    {
        // named route
        $router->setRoute(
            'html',
            new Route('/<action>.html', new Controller(HomeController::class))
        );

        // fallback (default) route
        $router->setDefault($this->defaultRoute());
    }

    /**
     * Default route points to namespace of controllers.
     *
     * @return RouteInterface
     */
    protected function defaultRoute(): RouteInterface
    {
        // handle all /controller/action like urls
        $route = new Route(
            '/[<controller>[/<action>]]',
            new Namespaced('App\\Controller')
        );

        return $route->withDefaults([
            'controller' => 'home',
            'action'     => 'index'
        ]);
    }
}
