<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use Spiral\Bootloader\DomainBootloader;
use Spiral\Core\CoreInterface;

/**
 * @link https://spiral.dev/docs/http-interceptors
 */
final class AppBootloader extends DomainBootloader
{
    protected const SINGLETONS = [CoreInterface::class => [self::class, 'domainCore']];
}
