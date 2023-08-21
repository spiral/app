<?php

declare(strict_types=1);

namespace Installer\Module\TemplateEngines\Twig\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Twig\Bootloader\TwigBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel
            ->addUse(ViewsBootloader::class)
            ->addUse(TwigBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                ViewsBootloader::class,
                TwigBootloader::class,
            ],
            comment: 'Views',
            priority: 12
        );
    }
}
