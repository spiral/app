<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\RoadRunnerBridge\Bootloaders;

final class RoadRunnerBridge extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::RoadRunnerBridge,
            generators: [
                new Bootloaders(),
            ]
        );
    }
}
