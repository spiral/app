<?php

declare(strict_types=1);

namespace Installer\Application;

use App\Application\Kernel;
use Composer\Package\PackageInterface;
use Installer\Package\Generator\RoadRunnerBridge\GRPCBootloader;
use Installer\Package\Package;
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
     * @param QuestionInterface[] $questions
     */
    public function __construct(
        string $name = 'gRPC',
        array $packages = [],
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
        array $resources = []
    ) {
        parent::__construct(
            name: $name,
            packages: $packages,
            autoload: $autoload,
            autoloadDev: $autoloadDev,
            questions: $questions,
            resources: $resources,
            generators: [
                new GRPCBootloader(),
            ],
            commands: [
                'composer rr:download-protoc',
            ]
        );
    }

    public function getKernelClass(): string
    {
        return Kernel::class;
    }
}
