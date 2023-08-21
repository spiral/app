<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\TemporalBridge\Package;
use Spiral\TemporalBridge\Bootloader\PrototypeBootloader;
use Spiral\TemporalBridge\Bootloader\TemporalBridgeBootloader;

final class TemporalBridge extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            PrototypeBootloader::class,
            TemporalBridgeBootloader::class,
        ];
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        return [
            'TEMPORAL_ADDRESS' => '127.0.0.1:7233',
            'TEMPORAL_TASK_QUEUE' => 'default',
        ];
    }
}
