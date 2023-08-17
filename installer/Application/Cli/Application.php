<?php

declare(strict_types=1);

namespace Installer\Application\Cli;

use Composer\Package\PackageInterface;
use Installer\Application\Cli\Generator\Bootloaders;
use Installer\Application\Cli\Generator\Skeleton;
use Installer\Internal\Application\AbstractApplication;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Package;
use Installer\Internal\Question\QuestionInterface;
use Installer\Internal\Readme\Block\FileBlock;
use Installer\Internal\Readme\Section;
use Installer\Module\Console\Generator\Skeleton as ConsoleSkeleton;
use Installer\Module\Dumper\Package as DumperPackage;
use Installer\Module\Exception\Generator\Skeleton as ExceptionSkeleton;
use Installer\Module\ExtMbString\Package as ExtMbStringPackage;
use Installer\Module\Psr7Implementation\Nyholm\Package as NyholmPsr7Implementation;
use Installer\Module\RoadRunnerBridge\Common\RoadRunnerCliPackage;

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
        string $name = 'Cli',
        array $packages = [
            new ExtMbStringPackage(),
            new NyholmPsr7Implementation(),
            new DumperPackage(),
            new RoadRunnerCliPackage(),
        ],
        array $autoload = [
            'psr-4' => [
                'App\\' => 'app/src',
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
            new Skeleton(),
            new ConsoleSkeleton(),
            new ExceptionSkeleton(),
        ],
        array $resources = [
            ':common:' => '',
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
            readme: $instructions
        );
    }

    protected function getDefaultReadme(): array
    {
        return \array_merge(parent::getDefaultReadme(), [
            Section::Usage->value => [
                new FileBlock(__DIR__ . '/readme/usage.md'),
            ],

            Section::ConsoleCommands->value => [
                new FileBlock(__DIR__ . '/readme/commands.md'),
            ],
        ]);
    }
}
