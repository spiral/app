<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Application\Stempler\FrameworkDirectives;
use App\Application\Stempler\PhpDirectives;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Stempler\Bootloader\StemplerBootloader as BaseStemplerBootloader;

final class CustomStemplerDirectivesBootloader extends Bootloader
{
    public function boot(BaseStemplerBootloader $stempler): void
    {
        $stempler->addDirective(PhpDirectives::class);
        $stempler->addDirective(FrameworkDirectives::class);
    }
}
