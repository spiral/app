<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\GRPC;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Internal\Readme\Block\FileBlock;
use Installer\Internal\Readme\Block\ListBlock;
use Installer\Internal\Readme\Section;
use Installer\Module\RoadRunnerBridge\GRPC\Generator\Bootloaders;
use Installer\Module\RoadRunnerBridge\GRPC\Generator\Config;
use Installer\Module\RoadRunnerBridge\GRPC\Generator\Services;
use Installer\Module\RoadRunnerBridge\GRPC\Generator\Skeleton;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::GRPC,
            generators: [
                new Bootloaders(),
                new Skeleton(),
                new Config(),
                //  new Services(),
            ],
            dependencies: [
                new ExtGRPC(),
            ],
        );
    }

    public function getReadme(): array
    {
        return [
            Section::Configuration->value => [
                new ListBlock([
                    'Configuration file: `app/config/grpc.php`',
                    'Documentation: https://spiral.dev/docs/grpc-configuration',
                ], $this->getTitle()),
            ],
            Section::ConsoleCommands->value => [
                new FileBlock(__DIR__ . '/../readme/grpc/install_protoc.md'),
                new FileBlock(__DIR__ . '/../readme/grpc/generate_proto.md'),
            ],
        ];
    }

    public function getResourcesPath(): string
    {
        return \dirname(__DIR__) . '/resources';
    }
}
