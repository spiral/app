<?php

declare(strict_types=1);

namespace Installer\Module\Http\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Bootloader\Http\HttpBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(HttpBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                HttpBootloader::class,
            ],
            comment: 'HTTP extensions',
            priority: 6
        );
    }
}
