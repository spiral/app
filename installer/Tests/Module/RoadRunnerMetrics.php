<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Spiral\RoadRunnerBridge\Bootloader\MetricsBootloader;

final class RoadRunnerMetrics extends AbstractModule
{
    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            MetricsBootloader::class,
        ];
    }
}
