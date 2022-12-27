<?php

declare(strict_types=1);

namespace Installer\Application;

use Composer\Package\PackageInterface;
use Installer\Application\Generator\GRPCApplicationBootloaders;
use Installer\Application\Generator\ViewRenderer;
use Installer\Generator\GeneratorInterface;
use Installer\Package\ExtGRPC;
use Installer\Package\ExtMbString;
use Installer\Package\Generator\RoadRunnerBridge\GRPCBootloader;
use Installer\Package\GRPC as PackageGRPC;
use Installer\Package\Package;
use Installer\Package\RoadRunnerBridge;
use Installer\Question\QuestionInterface;

/**
 * @psalm-import-type AutoloadRules from PackageInterface
 * @psalm-import-type DevAutoloadRules from PackageInterface
 */
final class GRPC extends AbstractApplication
{
    /**
     * @param Package[] $packages
     * @param AutoloadRules $autoload
     * @param DevAutoloadRules $autoloadDev
     * @param GeneratorInterface[] $generators
     * @param QuestionInterface[] $questions
     */
    public function __construct(
        string $name = 'gRPC',
        array $packages = [
            new ExtMbString(),
            new ExtGRPC(),
            new PackageGRPC(),
            new RoadRunnerBridge([]),
        ],
        array $autoload = [
            'psr-4' => [
                'App\\' => 'app/src',
                'GRPC\\' => 'generated/GRPC',
            ],
        ],
        array $autoloadDev = [
            'psr-4' => [
                'Tests\\' => 'tests',
            ],
        ],
        array $questions = [],
        array $generators = [
            new GRPCApplicationBootloaders(),
            new GRPCBootloader(),
            new ViewRenderer(),
        ],
        array $resources = [
            'common' => '',
            'applications/grpc/app' => 'app',
            'applications/grpc/proto' => 'proto',
        ],
        array $commands = [
            'rr download-protoc-binary',
        ],
        array $instructions = []
    ) {
        parent::__construct(
            name: $name,
            packages: $packages,
            autoload: $autoload,
            autoloadDev: $autoloadDev,
            questions: $questions,
            resources: $resources,
            generators: $generators,
            commands: $commands,
            instructions: $instructions
        );
    }
}
