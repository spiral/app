<?php

declare(strict_types=1);

namespace Installer\Package\Generator\SentryBridge;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Sentry\Bootloader\SentryReporterBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(SentryReporterBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [SentryReporterBootloader::class],
            comment: 'Sentry',
            priority: 10
        );
    }
}
