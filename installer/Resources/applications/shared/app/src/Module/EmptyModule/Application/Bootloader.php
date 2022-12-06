<?php

declare(strict_types=1);

namespace App\Module\EmptyModule\Application;

use App\Module\EmptyModule\Api;
use App\Module\EmptyModule\Internal;

final class Bootloader extends \Spiral\Boot\Bootloader\Bootloader
{
    protected const SINGLETONS = [
        Api\EmptyService::class => Internal\EmptyService::class,
    ];
}
