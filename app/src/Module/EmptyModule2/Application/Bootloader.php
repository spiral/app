<?php

declare(strict_types=1);

namespace App\Module\EmptyModule2\Application;

use App\Module\EmptyModule2\Api;
use App\Module\EmptyModule2\Internal;

final class Bootloader extends \Spiral\Boot\Bootloader\Bootloader
{
    protected const SINGLETONS = [
        Api\EmptyService::class => Internal\EmptyService::class,
    ];
}
