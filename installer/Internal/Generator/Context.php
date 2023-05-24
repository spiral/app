<?php

declare(strict_types=1);

namespace Installer\Internal\Generator;

use Installer\Internal\ApplicationInterface;
use Installer\Internal\Configurator\ResourceQueue;
use Installer\Internal\Generator\Bootloader\DomainInterceptorsConfigurator;
use Installer\Internal\Generator\Bootloader\ExceptionHandlerBootloaderConfigurator;
use Installer\Internal\Generator\Bootloader\RoutesBootloaderConfigurator;
use Installer\Internal\Generator\Env\Generator;
use Installer\Internal\Generator\Kernel\Configurator;

/**
 * The current state of the application files that are available for modification by the package.
 */
final class Context
{
    public function __construct(
        public readonly ApplicationInterface $application,
        public readonly Configurator $kernel,
        public readonly ExceptionHandlerBootloaderConfigurator $exceptionHandlerBootloader,
        public readonly RoutesBootloaderConfigurator $routesBootloader,
        public readonly DomainInterceptorsConfigurator $domainInterceptors,
        public readonly Generator $envConfigurator,
        public readonly string $applicationRoot,
        public ResourceQueue $resource
    ) {
    }
}
