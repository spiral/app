<?php

declare(strict_types=1);

namespace Installer\Package\Generator\NyholmBridge;

use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\Nyholm\Bootloader\NyholmBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->loadAppend(NyholmBootloader::class, GuardBootloader::class);
    }
}
