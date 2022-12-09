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
        ],
        array $instructions = [
            'The RoadRunner configuration is available in the <comment>.rr.yaml</comment> file at the root of the application',
            'Documentation: <comment>https://spiral.dev/docs/packages-roadrunner-bridge</comment>',
        ]
    ) {
        parent::__construct(Packages::RoadRunnerBridge, $resources, $generators, $instructions);
    }
}
