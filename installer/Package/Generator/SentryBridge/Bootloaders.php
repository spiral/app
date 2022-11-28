<?php

declare(strict_types=1);

namespace Installer\Package\Generator\SentryBridge;

use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\Sentry\Bootloader\SentryReporterBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->loadAppend(SentryReporterBootloader::class, GuardBootloader::class);
    }
}
