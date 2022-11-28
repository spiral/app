<?php

declare(strict_types=1);

namespace Installer\Package\Generator\TwigBridge;

use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\Bootloader\Views\TranslatedCacheBootloader;
use Spiral\Twig\Bootloader\TwigBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(ViewsBootloader::class);
        $context->kernel->addUse(TranslatedCacheBootloader::class);
        $context->kernel->addUse(TwigBootloader::class);

        $context->kernel->loadAppend(ViewsBootloader::class, GuardBootloader::class);
        $context->kernel->loadAppend(TranslatedCacheBootloader::class, ViewsBootloader::class);
        $context->kernel->loadAppend(TwigBootloader::class, TranslatedCacheBootloader::class);
    }
}
