<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\GRPC;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::GRPC,
            instructions: [
                'Configuration file: <comment>app/config/grpc.php</comment>',
                'Use <comment>php app.php grpc:generate</comment> console command to generate gRPC stubs',
                'Documentation: <comment>https://spiral.dev/docs/grpc-configuration</comment>',
            ],
            dependencies: [
                new ExtGRPC(),
            ],
        );
    }
}