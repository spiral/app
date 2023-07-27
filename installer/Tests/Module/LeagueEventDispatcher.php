<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\EventDispatcher\League\Package;
use Spiral\Events\Bootloader\EventsBootloader;
use Spiral\League\Event\Bootloader\EventBootloader;

final class LeagueEventDispatcher extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            EventsBootloader::class,
            EventBootloader::class,
        ];
    }
}
