<?php

declare(strict_types=1);

namespace Installer\Application;

use Composer\Package\PackageInterface;
use Installer\Application\Generator\ViewRenderer;
use Installer\Application\Generator\WebApplicationBootloaders;
use Installer\Application\Generator\WebApplicationEnv;
use Installer\Application\Generator\WebApplicationSkeleton;
use Installer\Generator\GeneratorInterface;
use Installer\Package\ExtMbString;
use Installer\Package\NyholmBridge;
use Installer\Package\Package;
use Installer\Package\RoadRunnerBridge;
use Installer\Package\SapiBridge;
use Installer\Package\YiiErrorHandler;
use Installer\Question\QuestionInterface;

/**
 * @psalm-import-type AutoloadRules from PackageInterface
 * @psalm-import-type DevAutoloadRules from PackageInterface
 */
final class Web extends AbstractApplication
{
    /**
     * @param Package[] $packages
     * @param AutoloadRules $autoload
     * @param DevAutoloadRules $autoloadDev
     * @param GeneratorInterface[] $generators
     * @param QuestionInterface[] $questions
     */
    public function __construct(
        string $name = 'Web',
        array $packages = [
            new ExtMbString(),
            new NyholmBridge(),
            new YiiErrorHandler(),
            new RoadRunnerBridge(),
        ],
        array $autoload = [
            'psr-4' => [
                'App\\' => 'app/src',
            ],
            'files' => [
                'app/src/Application/helpers.php',
            ],
        ],
        array $autoloadDev = [
            'psr-4' => [
                'Tests\\' => 'tests',
            ],
        ],
        array $questions = [],
        array $generators = [
            new WebApplicationBootloaders(),
            new ViewRenderer(),
            new WebApplicationEnv(),
            new WebApplicationSkeleton(),
        ],
        array $resources = [
            'common' => '',
            'applications/web/app' => 'app',
            'applications/web/public' => 'public',
        ],
        array $commands = [],
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

        $this->useRoadRunnerPlugin('http');
    }
}
