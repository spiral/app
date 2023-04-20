<?php

declare(strict_types=1);

namespace App\Application;

use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader as Framework;
use Spiral\Cycle\Bootloader as CycleBridge;
use Spiral\DotEnv\Bootloader as DotEnv;
use Spiral\Monolog\Bootloader as Monolog;
use Spiral\Nyholm\Bootloader as Nyholm;
use Spiral\Prototype\Bootloader as Prototype;
use Spiral\RoadRunnerBridge\Bootloader as RoadRunnerBridge;
use Spiral\Scaffolder\Bootloader as Scaffolder;
use Spiral\Stempler\Bootloader as Stempler;
use Spiral\Tokenizer\Bootloader\TokenizerBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;
use Spiral\YiiErrorHandler\Bootloader\YiiErrorHandlerBootloader;

class Kernel extends \Spiral\Framework\Kernel
{
    public const VERSION = 'v3.7.0';

    /**
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected function defineBootloaders(): array
    {
        return [
            // Logging and exceptions handling
            Monolog\MonologBootloader::class,
            YiiErrorHandlerBootloader::class,

            // Application specific logs
            Bootloader\LoggingBootloader::class,

            // Core Services
            Framework\SnapshotsBootloader::class,
            Framework\I18nBootloader::class,

            // Security and validation
            Framework\Security\EncrypterBootloader::class,
            // ValidationBootloader::class,
            // Framework\Security\FiltersBootloader::class,
            // Framework\Security\GuardBootloader::class,

            // HTTP extensions
            Nyholm\NyholmBootloader::class,
            Framework\Http\RouterBootloader::class,
            Framework\Http\JsonPayloadsBootloader::class,
            // Framework\Http\CookiesBootloader::class,
            // Framework\Http\SessionBootloader::class,
            // Framework\Http\CsrfBootloader::class,
            // Framework\Http\PaginationBootloader::class,

            // RoadRunner
            RoadRunnerBridge\CacheBootloader::class,
            RoadRunnerBridge\HttpBootloader::class,
            RoadRunnerBridge\QueueBootloader::class,

            // Databases
            CycleBridge\DatabaseBootloader::class,
            CycleBridge\MigrationsBootloader::class,
            // CycleBridge\DisconnectsBootloader::class,

            // ORM
            // CycleBridge\SchemaBootloader::class,
            // CycleBridge\CycleOrmBootloader::class,
            // CycleBridge\AnnotatedBootloader::class,
            // CycleBridge\CommandBootloader::class,

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
            // Framework\DebugBootloader::class,
            // Framework\Debug\LogCollectorBootloader::class,
            // Framework\Debug\HttpCollectorBootloader::class,

            RoadRunnerBridge\CommandBootloader::class,

            Bootloader\RoutesBootloader::class,
            Bootloader\CustomStemplerDirectivesBootloader::class,
            Bootloader\ExceptionHandlerBootloader::class,

            // Fast code prototyping
            Prototype\PrototypeBootloader::class,

            \Spiral\Serializer\Symfony\Bootloader\SerializerBootloader::class,
        ];
    }

    protected function defineSystemBootloaders(): array
    {
        return [
            CoreBootloader::class,
            TokenizerBootloader::class,
            DotEnv\DotenvBootloader::class,
        ];
    }
}
