<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\Common;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Module\RoadRunnerBridge\Common\Generator\Bootloaders;
use Installer\Module\RoadRunnerBridge\Common\Generator\CacheConfig;
use Installer\Module\RoadRunnerBridge\Common\Generator\Env;
use Installer\Module\RoadRunnerBridge\Common\Generator\QueueConfig;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::RoadRunnerBridge,
            generators: [
                new QueueConfig(),
                new CacheConfig(),
                new Bootloaders(),
                new Env(),
            ],
            instructions: [
                'The settings for RoadRunner are in a file named <comment>.rr.yaml</comment> at the main folder of the app.',
                'Documentation: <comment>https://spiral.dev/docs/start-server</comment>',
            ],
            dependencies: [
                new RoadRunnerCliPackage(),
                new ExtSocketsPackage(),
            ],
        );
    }

    public function getResourcesPath(): string
    {
        return dirname(__DIR__) . '/resources';
    }
}
