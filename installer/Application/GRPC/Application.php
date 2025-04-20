<?php

declare(strict_types=1);

namespace Installer\Application\GRPC;

use Composer\Package\PackageInterface;
use Installer\Application\GRPC\Generator\Bootloaders;
use Installer\Application\Web\Generator\ViewRenderer;
use Installer\Internal\Application\AbstractApplication;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Package;
use Installer\Internal\Question\QuestionInterface;
use Installer\Module\Console\Generator\Skeleton as ConsoleSkeleton;
use Installer\Module\Dumper\Package as DumperPackage;
use Installer\Module\Exception\Generator\Skeleton as ExceptionSkeleton;
use Installer\Module\ExtMbString\Package as ExtMbStringPackage;
use Installer\Module\Psr7Implementation\Nyholm\Package as NyholmPsr7Implementation;
use Installer\Module\RoadRunnerBridge\Common\Package as RoadRunnerBridgePackage;
use Installer\Module\RoadRunnerBridge\GRPC\Package as RoadRunnerGRPCPackage;

/**
 * @psalm-import-type AutoloadRules from PackageInterface
 * @psalm-import-type DevAutoloadRules from PackageInterface
 */
final class Application extends AbstractApplication
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
            new ExtMbStringPackage(),
            new NyholmPsr7Implementation(),
            new RoadRunnerGRPCPackage(),
            new RoadRunnerBridgePackage([]),
            new DumperPackage(),
        ],
        array $autoload = [
            'psr-4' => [
                'App\\' => 'app/src',
                'GRPC\\' => 'generated',
            ],
        ],
        array $autoloadDev = [
            'psr-4' => [
                'Tests\\' => 'tests',
            ],
        ],
        array $questions = [],
        array $generators = [
            new Bootloaders(),
            new ConsoleSkeleton(),
            new ExceptionSkeleton(),
            new ViewRenderer(),
        ],
        array $resources = [
            ':common:' => '',
        ],
        array $commands = [
            'rr download-protoc-binary',
        ],
        array $instructions = [
        ],
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
            readme: $instructions,
        );
    }
}
