<?php

declare(strict_types=1);

namespace Tests;

use App\Application\Bootloader\AppBootloader;
use App\Application\Bootloader\ExceptionHandlerBootloader;
use App\Application\Bootloader\RoutesBootloader;
use App\Application\Kernel;
use Installer\Internal\ClassMetadataInterface;
use Installer\Internal\ClassMetadataRepositoryInterface;

final class FakeClassMetadataRepository implements ClassMetadataRepositoryInterface
{
    public function __construct(
        private readonly string $appPath,
    ) {
    }

    public function getMetaData(string $class): ClassMetadataInterface
    {
        return match ($class) {
            AppBootloader::class => new ManualClassMetadata(
                name: 'AppBootloader',
                path: $this->appPath . '/app/src/Application/Bootloader/AppBootloader.php',
                namespace: 'App\Application\Bootloader'
            ),
            ExceptionHandlerBootloader::class => new ManualClassMetadata(
                name: 'ExceptionHandlerBootloader',
                path: $this->appPath . '/app/src/Application/Bootloader/ExceptionHandlerBootloader.php',
                namespace: 'App\Application\Bootloader'
            ),
            RoutesBootloader::class => new ManualClassMetadata(
                name: 'RoutesBootloader',
                path: $this->appPath . '/app/src/Application/Bootloader/RoutesBootloader.php',
                namespace: 'App\Application\Bootloader'
            ),
            Kernel::class => new ManualClassMetadata(
                name: 'Kernel',
                path: $this->appPath . '/app/src/Application/Kernel.php',
                namespace: 'App\Application'
            ),
        };
    }
}
