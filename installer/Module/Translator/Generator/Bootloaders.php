<?php

declare(strict_types=1);

namespace Installer\Module\Translator\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Bootloader\I18nBootloader;
use Spiral\Bootloader\Views\TranslatedCacheBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(I18nBootloader::class);
        $context->kernel->addUse(TranslatedCacheBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                I18nBootloader::class,
                TranslatedCacheBootloader::class,
            ],
            comment: 'Internationalization',
            priority: 13
        );
    }
}
