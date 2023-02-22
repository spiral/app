<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;

final class GRPC extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [],
        array $generators = [],
        array $instructions = [
            'Configuration file: <comment>app/config/grpc.php</comment>',
            'Use <comment>php app.php grpc:generate</comment> console command to generate gRPC stubs',
            'Documentation: <comment>https://spiral.dev/docs/grpc-configuration</comment>',
        ]
    ) {
        parent::__construct(Packages::GRPC, $resources, $generators, $instructions);
    }
}
