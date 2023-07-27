<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Psr7Implementation\Nyholm\Package;
use Spiral\Nyholm\Bootloader\NyholmBootloader;

final class NyholmPsr7 extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            NyholmBootloader::class,
        ];
    }
}
