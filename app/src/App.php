<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace App;

use App\Bootloader\LoggingBootloader;
use App\Bootloader\RoutesBootloader;
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
        Bootloader\Security\ValidationBootloader::class,
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

        // Views and view translation
        Bootloader\Views\ViewsBootloader::class,
        Bootloader\Views\TranslatedCacheBootloader::class,

        // Jobs and Queue
        Bootloader\Jobs\JobsBootloader::class,

        // Extensions and bridges
        MonologBootloader::class,
        TwigBootloader::class,

        // Framework commands
        Bootloader\CommandBootloader::class
    ];

    /*
     * Application specific services and extensions.
     */
    protected const APP = [
        RoutesBootloader::class,
        LoggingBootloader::class
    ];
}