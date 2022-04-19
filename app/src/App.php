<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App;

use App\Bootloader;
use Spiral\Bootloader as Framework;
use Spiral\DotEnv\Bootloader as DotEnv;
use Spiral\Framework\Kernel;
use Spiral\Monolog\Bootloader as Monolog;
use Spiral\Nyholm\Bootloader as Nyholm;
use Spiral\Prototype\Bootloader as Prototype;
use Spiral\Scaffolder\Bootloader as Scaffolder;
use Spiral\Stempler\Bootloader as Stempler;
use Spiral\Cycle\Bootloader as CycleBridge;
use Spiral\RoadRunnerBridge\Bootloader as RoadRunnerBridge;
use Spiral\Validation\Bootloader\ValidationBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

class App extends Kernel
{
    /*
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected const LOAD = [

        // RoadRunner
        RoadRunnerBridge\CacheBootloader::class,
        RoadRunnerBridge\GRPCBootloader::class,
        RoadRunnerBridge\HttpBootloader::class,
        RoadRunnerBridge\QueueBootloader::class,
        RoadRunnerBridge\RoadRunnerBootloader::class,

        // Base extensions
        DotEnv\DotenvBootloader::class,
        Monolog\MonologBootloader::class,

        // Application specific logs
        Bootloader\LoggingBootloader::class,

        // Core Services
        Framework\SnapshotsBootloader::class,
        Framework\I18nBootloader::class,

        // Security and validation
        Framework\Security\EncrypterBootloader::class,
        ValidationBootloader::class,
        Framework\Security\FiltersBootloader::class,
        Framework\Security\GuardBootloader::class,

        // HTTP extensions
        Nyholm\NyholmBootloader::class,
        Framework\Http\RouterBootloader::class,
        Framework\Http\ErrorHandlerBootloader::class,
        Framework\Http\JsonPayloadsBootloader::class,
        Framework\Http\CookiesBootloader::class,
        Framework\Http\SessionBootloader::class,
        Framework\Http\CsrfBootloader::class,
        Framework\Http\PaginationBootloader::class,

        // Databases
        CycleBridge\DatabaseBootloader::class,
        CycleBridge\MigrationsBootloader::class,
        // CycleBridge\DisconnectsBootloader::class,

        // ORM
        CycleBridge\SchemaBootloader::class,
        CycleBridge\CycleOrmBootloader::class,
        CycleBridge\AnnotatedBootloader::class,
        CycleBridge\CommandBootloader::class,

        // DataGrid
        // CycleBridge\DataGridBootloader::class,

        // Auth
        // CycleBridge\AuthTokensBootloader::class,

        // Entity checker
        // CycleBridge\ValidationBootloader::class,

        // Views and view translation
        ViewsBootloader::class,
        Framework\Views\TranslatedCacheBootloader::class,


        // Extensions and bridges
        Stempler\StemplerBootloader::class,

        // Framework commands
        Framework\CommandBootloader::class,
        Scaffolder\ScaffolderBootloader::class,

        // Debug and debug extensions
        Framework\ExceptionHandlerBootloader::class,
        Framework\DebugBootloader::class,
        Framework\Debug\LogCollectorBootloader::class,
        Framework\Debug\HttpCollectorBootloader::class,

        RoadRunnerBridge\CommandBootloader::class,
    ];

    /*
     * Application specific services and extensions.
     */
    protected const APP = [
        Bootloader\LocaleSelectorBootloader::class,
        Bootloader\RoutesBootloader::class,

        // fast code prototyping
        Prototype\PrototypeBootloader::class,
    ];
}
