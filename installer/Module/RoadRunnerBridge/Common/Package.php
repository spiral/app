<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\Common;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Internal\Readme\Block\FileBlock;
use Installer\Internal\Readme\Block\LinkString;
use Installer\Internal\Readme\Block\ListBlock;
use Installer\Internal\Readme\Section;
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
            dependencies: [
                new RoadRunnerCliPackage(),
                new ExtSocketsPackage(),
            ],
        );
    }

    public function getReadme(): array
    {
        return [
            Section::Configuration->value => [
                new ListBlock([
                    'The settings for RoadRunner are in a file `.rr.yaml` at the main folder of the app.',
                    'Documentation: https://spiral.dev/docs/start-server',
                ], $this->getTitle()),
            ],
            Section::ConsoleCommands->value => [
                new FileBlock(__DIR__ . '/../readme/install_rr.md'),
            ],
        ];
    }

    public function getResourcesPath(): string
    {
        return dirname(__DIR__) . '/resources';
    }
}
