<?php

declare(strict_types=1);

namespace Installer\Application\Cli;

use Composer\Package\PackageInterface;
use Installer\Application\Cli\Generator\Bootloaders;
use Installer\Application\Cli\Generator\Skeleton;
use Installer\Application\Web\Generator\ViewRenderer;
use Installer\Internal\AbstractApplication;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Package;
use Installer\Internal\Question\QuestionInterface;
use Installer\Module\Console\Generator\Skeleton as ConsoleSkeleton;
use Installer\Module\Exception\Generator\Skeleton as ExceptionSkeleton;

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
        array $packages = [],
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
            new ViewRenderer(),
            new Skeleton(),
            new ConsoleSkeleton(),
            new ExceptionSkeleton(),
        ],
        array $resources = [
            '/../../Custom/resources/common' => '',
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
    }
}
