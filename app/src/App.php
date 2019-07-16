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
use Spiral\DotEnv\Bootloader as DotEnv;
use Spiral\Framework\Kernel;
use Spiral\Monolog\Bootloader as Monolog;
use Spiral\Nyholm\Bootloader as Nyholm;
use Spiral\Twig\Bootloader as Twig;

class App extends Kernel
{
    /*
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected const LOAD = [
        // Environment configuration
        DotEnv\DotenvBootloader::class,

        // Core Services
        Bootloader\DebugBootloader::class,
        Bootloader\SnapshotsBootloader::class,
        Bootloader\I18nBootloader::class,

        // Security and validation
        Bootloader\Security\EncrypterBootloader::class,
        Bootloader\Security\ValidationBootloader::class,
        Bootloader\Security\FiltersBootloader::class,
        Bootloader\Security\GuardBootloader::class,

        // HTTP extensions
        Nyholm\NyholmBootloader::class,
        Bootloader\Http\RouterBootloader::class,
        Bootloader\Http\ErrorHandlerBootloader::class,
        Bootloader\Http\CookiesBootloader::class,
        Bootloader\Http\SessionBootloader::class,
        Bootloader\Http\CsrfBootloader::class,
        Bootloader\Http\PaginationBootloader::class,

        // Databases
        Bootloader\Database\DatabaseBootloader::class,
        Bootloader\Database\MigrationsBootloader::class,

        // ORM
        Bootloader\Cycle\CycleBootloader::class,
        Bootloader\Cycle\ProxiesBootloader::class,
        Bootloader\Cycle\AnnotatedBootloader::class,

        // Views and view translation
        Bootloader\Views\ViewsBootloader::class,
        Bootloader\Views\TranslatedCacheBootloader::class,

        // Additional dispatchers
        Bootloader\Jobs\JobsBootloader::class,

        // Extensions and bridges
        Monolog\MonologBootloader::class,
        Twig\TwigBootloader::class,

        // Framework commands
        Bootloader\CommandBootloader::class
    ];

    /*
     * Application specific services and extensions.
     */
    protected const APP = [
        RoutesBootloader::class,
        LoggingBootloader::class,
    ];
}