<?php

declare(strict_types=1);

namespace Installer\Module\TemplateEngines\PlainPHP\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Views\Bootloader\ViewsBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(ViewsBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                ViewsBootloader::class,
            ],
            comment: 'Views',
            priority: 12,
        );
    }
}
