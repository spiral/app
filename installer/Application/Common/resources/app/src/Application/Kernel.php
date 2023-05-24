<?php

declare(strict_types=1);

namespace App\Application;

use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader as Framework;
use Spiral\DotEnv\Bootloader\DotenvBootloader;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Nyholm\Bootloader\NyholmBootloader;
use Spiral\Prototype\Bootloader\PrototypeBootloader;
use Spiral\RoadRunnerBridge\Bootloader as RoadRunnerBridge;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader;
use Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader;
use Spiral\YiiErrorHandler\Bootloader\YiiErrorHandlerBootloader;

class Kernel extends \Spiral\Framework\Kernel
{
    protected const SYSTEM = [];
    protected const LOAD = [];
    protected const APP = [];

    public function defineSystemBootloaders(): array
    {
        return [
            CoreBootloader::class,
            TokenizerListenerBootloader::class,
            DotenvBootloader::class,
        ];
    }

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
            RoadRunnerBridge\HttpBootloader::class,

            // Core Services
            Framework\SnapshotsBootloader::class,

            // Security and validation
            Framework\Security\EncrypterBootloader::class,
            Framework\Security\FiltersBootloader::class,
            Framework\Security\GuardBootloader::class,

            // HTTP extensions
            Framework\Http\RouterBootloader::class,
            Framework\Http\JsonPayloadsBootloader::class,
            Framework\Http\CookiesBootloader::class,
            Framework\Http\SessionBootloader::class,
            Framework\Http\CsrfBootloader::class,
            Framework\Http\PaginationBootloader::class,

            NyholmBootloader::class,

            // Console commands
            Framework\CommandBootloader::class,
            RoadRunnerBridge\CommandBootloader::class,
            ScaffolderBootloader::class,

            // Configure route groups, middleware for route groups
            Bootloader\RoutesBootloader::class,

            // Fast code prototyping
            PrototypeBootloader::class,
        ];
    }
}
