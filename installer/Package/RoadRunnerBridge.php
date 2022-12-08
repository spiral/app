<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\RoadRunnerBridge\Bootloaders;
use Installer\Package\Generator\RoadRunnerBridge\Env;

final class RoadRunnerBridge extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [],
        array $generators = [
            new Bootloaders(),
            new Env(),
        ]
    ) {
        parent::__construct(Packages::RoadRunnerBridge, $resources, $generators);
    }
}
