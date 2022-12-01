<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\Scheduler\Bootloaders;

final class Scheduler extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::Scheduler,
            generators: [
                new Bootloaders()
            ]
        );
    }
}
