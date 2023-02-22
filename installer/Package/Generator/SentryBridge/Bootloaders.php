<?php

declare(strict_types=1);

namespace Installer\Package\Generator\SentryBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Bootloader\Debug\HttpCollectorBootloader;
use Spiral\Bootloader\Debug\LogCollectorBootloader;
use Spiral\Bootloader\DebugBootloader;
use Spiral\Sentry\Bootloader\SentryReporterBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(SentryReporterBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                SentryReporterBootloader::class,
                DebugBootloader::class,
                LogCollectorBootloader::class,
                HttpCollectorBootloader::class,
            ],
            comment: 'Sentry and Data collectors',
            priority: 10
        );
    }
}
