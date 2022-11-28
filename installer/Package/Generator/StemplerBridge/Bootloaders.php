<?php

declare(strict_types=1);

namespace Installer\Package\Generator\StemplerBridge;

use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\Bootloader\Views\TranslatedCacheBootloader;
use Spiral\Stempler\Bootloader\StemplerBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->loadAppend(ViewsBootloader::class, GuardBootloader::class);
        $context->kernel->loadAppend(TranslatedCacheBootloader::class, ViewsBootloader::class);
        $context->kernel->loadAppend(StemplerBootloader::class, TranslatedCacheBootloader::class);
    }
}
