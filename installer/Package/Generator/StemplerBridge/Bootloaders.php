<?php

declare(strict_types=1);

namespace Installer\Package\Generator\StemplerBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Bootloader\Views\TranslatedCacheBootloader;
use Spiral\Stempler\Bootloader\StemplerBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(ViewsBootloader::class);
        $context->kernel->addUse(TranslatedCacheBootloader::class);
        $context->kernel->addUse(StemplerBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                ViewsBootloader::class,
                TranslatedCacheBootloader::class,
                StemplerBootloader::class,
            ],
            comment: 'Views and view translation',
            priority: 12
        );
    }
}
