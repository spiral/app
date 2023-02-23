<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\RoadRunnerBridge\Bootloaders;
use Installer\Package\Generator\RoadRunnerBridge\CacheConfig;
use Installer\Package\Generator\RoadRunnerBridge\Env;
use Installer\Package\Generator\RoadRunnerBridge\QueueConfig;

final class RoadRunnerBridge extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [],
        array $generators = [
            new QueueConfig(),
            new CacheConfig(),
            new Bootloaders(),
            new Env(),
        ],
        array $instructions = [
            'The settings for RoadRunner are in a file named <comment>.rr.yaml</comment> at the main folder of the app.',
            'Documentation: <comment>https://spiral.dev/docs/start-server</comment>',
        ]
    ) {
        parent::__construct(Packages::RoadRunnerBridge, $resources, $generators, $instructions);
    }
}
