<?php

declare(strict_types=1);

use App\Api\Web\Controller\HomeController;
use Spiral\Router\Loader\Configurator\RoutingConfigurator;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the Router within a group which
| contains the "web" middleware group.
|
*/
return function (RoutingConfigurator $routes): void {
    $routes->add('home', '/<action>')->controller(HomeController::class);
};
