<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace App;

use App\Bootloader\RouteBootloader;
use Spiral\Bootloader;
use Spiral\Framework\Kernel;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Twig\Bootloader\TwigBootloader;

class App extends Kernel
{
    /*
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected const LOAD = [
        // Core Services
        Bootloader\DebugBootloader::class,
        Bootloader\SnapshotsBootloader::class,
        Bootloader\I18nBootloader::class,

        // Security and validation
        Bootloader\Security\EncrypterBootloader::class,
        Bootloader\Security\FiltersBootloader::class,
        Bootloader\Security\RbacBootloader::class,

        // HTTP extensions
        Bootloader\Http\MvcBootloader::class,
        Bootloader\Http\ErrorHandlerBootloader::class,
        Bootloader\Http\CookiesBootloader::class,
        Bootloader\Http\SessionBootloader::class,
        Bootloader\Http\CsrfBootloader::class,
        Bootloader\Http\PaginationBootloader::class,

        // Databases and ORM
        Bootloader\Database\DatabaseBootloader::class,
        Bootloader\Database\MigrationsBootloader::class,
        Bootloader\Cycle\CycleBootloader::class,

        // Jobs and Queue
        Bootloader\Jobs\JobsBootloader::class,
    ];

    /*
     * Application specific services and extensions.
     */
    protected const APP = [
        // Extensions and bridges
        MonologBootloader::class,
        TwigBootloader::class,

        // Routing
        RouteBootloader::class,

        // Framework commands
        Bootloader\CommandBootloader::class
    ];
}