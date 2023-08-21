<?php

declare(strict_types=1);

namespace Installer\Module\Psr7Implementation\Nyholm\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Bootloader\Http\RouterBootloader;
use Spiral\Nyholm\Bootloader\NyholmBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(NyholmBootloader::class);

        $context->kernel->load->prepend(NyholmBootloader::class, RouterBootloader::class);
    }
}
