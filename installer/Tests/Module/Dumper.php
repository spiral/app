<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Dumper\Package;
use Spiral\Debug\Bootloader\DumperBootloader;

final class Dumper extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            DumperBootloader::class,
        ];
    }
}
