<?php

declare(strict_types=1);

namespace App\Application;

use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader as Framework;
use Spiral\Bootloader\Http\HttpBootloader;
use Spiral\Bootloader\I18nBootloader;
use Spiral\Bootloader\Views\TranslatedCacheBootloader;
use Spiral\Cache\Bootloader\CacheBootloader;
use Spiral\Cycle\Bootloader as CycleBridge;
use Spiral\Debug\Bootloader\DumperBootloader;
use Spiral\DotEnv\Bootloader\DotenvBootloader;
use Spiral\Events\Bootloader\EventsBootloader;
use Spiral\League\Event\Bootloader\EventBootloader;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Nyholm\Bootloader\NyholmBootloader;
use Spiral\Prototype\Bootloader\PrototypeBootloader;
use Spiral\Queue\Bootloader\QueueBootloader;
use Spiral\RoadRunnerBridge\Bootloader as RoadRunnerBridge;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader;
use Spiral\SendIt\Bootloader\MailerBootloader;
use Spiral\Stempler\Bootloader\StemplerBootloader;
use Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;
use Spiral\YiiErrorHandler\Bootloader\YiiErrorHandlerBootloader;

/**
 * @psalm-suppress ClassMustBeFinal
 */
class Kernel extends \Spiral\Framework\Kernel
{
    #[\Override]
    public function defineSystemBootloaders(): array
    {
        return [
            CoreBootloader::class,
            DotenvBootloader::class,
            TokenizerListenerBootloader::class,

            DumperBootloader::class,
        ];
    }

    /**
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    #[\Override]
    public function defineBootloaders(): array
    {
        return [
            // Logging and exceptions handling
            MonologBootloader::class,
            YiiErrorHandlerBootloader::class,
            Bootloader\ExceptionHandlerBootloader::class,

            // Application specific logs
            Bootloader\LoggingBootloader::class,

            // RoadRunner
            RoadRunnerBridge\LoggerBootloader::class,
            RoadRunnerBridge\QueueBootloader::class,
            RoadRunnerBridge\HttpBootloader::class,
            RoadRunnerBridge\CacheBootloader::class,

            // Core Services
            Framework\SnapshotsBootloader::class,

            // Security and validation
            Framework\Security\EncrypterBootloader::class,
            Framework\Security\FiltersBootloader::class,
            Framework\Security\GuardBootloader::class,

            // HTTP extensions
            HttpBootloader::class,
            Framework\Http\RouterBootloader::class,
            Framework\Http\JsonPayloadsBootloader::class,
            Framework\Http\CookiesBootloader::class,
            Framework\Http\SessionBootloader::class,
            Framework\Http\CsrfBootloader::class,
            Framework\Http\PaginationBootloader::class,

            // Databases
            CycleBridge\DatabaseBootloader::class,
            CycleBridge\MigrationsBootloader::class,

            // ORM
            CycleBridge\SchemaBootloader::class,
            CycleBridge\CycleOrmBootloader::class,
            CycleBridge\AnnotatedBootloader::class,

            // Event Dispatcher
            EventsBootloader::class,
            EventBootloader::class,

            // Views
            ViewsBootloader::class,
            StemplerBootloader::class,

            // Queue
            QueueBootloader::class,

            // Cache
            CacheBootloader::class,

            // Internationalization
            I18nBootloader::class,
            TranslatedCacheBootloader::class,

            // Mailer
            MailerBootloader::class,

            NyholmBootloader::class,

            // Console commands
            Framework\CommandBootloader::class,
            RoadRunnerBridge\CommandBootloader::class,
            CycleBridge\CommandBootloader::class,
            ScaffolderBootloader::class,
            RoadRunnerBridge\ScaffolderBootloader::class,
            CycleBridge\ScaffolderBootloader::class,

            // Fast code prototyping
            PrototypeBootloader::class,

            // Configure route groups, middleware for route groups
            Bootloader\RoutesBootloader::class,
            Bootloader\CustomStemplerDirectivesBootloader::class,
        ];
    }

    #[\Override]
    public function defineAppBootloaders(): array
    {
        return [
            // User Domain
            Bootloader\PersistenceBootloader::class,

            // Application domain
            Bootloader\AppBootloader::class,
        ];
    }
}
