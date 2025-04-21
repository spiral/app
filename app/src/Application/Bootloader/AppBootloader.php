<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use Spiral\Bootloader\DomainBootloader;
use Spiral\Cycle\Interceptor\CycleInterceptor;
use Spiral\Domain\GuardInterceptor;
use Spiral\Interceptors\HandlerInterface;

/**
 * @link https://spiral.dev/docs/http-interceptors
 */
final class AppBootloader extends DomainBootloader
{
    protected const SINGLETONS = [HandlerInterface::class => [self::class, 'domainCore']];

    protected const INTERCEPTORS = [
        CycleInterceptor::class,
        GuardInterceptor::class,
    ];
}
