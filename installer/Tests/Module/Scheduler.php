<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Scheduler\Package;
use Spiral\Scheduler\Bootloader\SchedulerBootloader;

final class Scheduler extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            SchedulerBootloader::class,
        ];
    }
}
