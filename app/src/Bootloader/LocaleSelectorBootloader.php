<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace App\Bootloader;

use App\Middleware\LocaleSelector;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\Http\HttpBootloader;

class LocaleSelectorBootloader extends Bootloader
{
    public function boot(HttpBootloader $http): void
    {
        $http->addMiddleware(LocaleSelector::class);
    }
}
