<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\SentryBridge\Package;
use Spiral\Sentry\Bootloader\SentryReporterBootloader;
use Spiral\Bootloader\DebugBootloader;
use Spiral\Bootloader\Debug\LogCollectorBootloader;
use Spiral\Bootloader\Debug\HttpCollectorBootloader;

final class SentryBridge extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            SentryReporterBootloader::class,
            DebugBootloader::class,
            LogCollectorBootloader::class,
            HttpCollectorBootloader::class,
        ];
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        return [
            'SENTRY_DSN' => null,
        ];
    }
}
