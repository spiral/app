<?php

declare(strict_types=1);

namespace Installer\Package\Generator\SapiBridge;

use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\Sapi\Bootloader\SapiBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(SapiBootloader::class);

        $context->kernel->loadAppend(SapiBootloader::class, GuardBootloader::class);
    }
}
