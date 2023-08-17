<?php

declare(strict_types=1);

namespace Installer\Application\Web;

use Composer\Package\PackageInterface;
use Installer\Application\Package;
use Installer\Application\Web\Generator\Bootloaders;
use Installer\Application\Web\Generator\Env;
use Installer\Application\Web\Generator\Interceptors;
use Installer\Application\Web\Generator\Skeleton;
use Installer\Application\Web\Generator\ViewRenderer;
use Installer\Internal\Application\AbstractApplication;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Question\QuestionInterface;
use Installer\Internal\Readme\Block\FileBlock;
use Installer\Internal\Readme\Section;
use Installer\Module\Console\Generator\Skeleton as ConsoleSkeleton;
use Installer\Module\Dumper\Package as DumperPackage;
use Installer\Module\ErrorHandler\Yii\Package as YiiErrorHandlerPackage;
use Installer\Module\Exception\Generator\Skeleton as ExceptionSkeleton;
use Installer\Module\ExtMbString\Package as ExtMbStringPackage;
use Installer\Module\Psr7Implementation\Nyholm\Package as NyholmPsr7Implementation;
use Installer\Module\RoadRunnerBridge\Common\Package as RoadRunnerBridgePackage;

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
        string $name = 'Web',
        array $packages = [
            new ExtMbStringPackage(),
            new NyholmPsr7Implementation(),
            new RoadRunnerBridgePackage(),
            new YiiErrorHandlerPackage(),
            new DumperPackage(),
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
            new Interceptors(),
            new ViewRenderer(),
            new Env(),
            new Skeleton(),
            new ConsoleSkeleton(),
            new ExceptionSkeleton(),
        ],
        array $resources = [
            ':common:' => '',
            ':web:/app' => 'app',
            ':web:/public' => 'public',
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

        $this->useRoadRunnerPlugin('http');
    }

    public function hasAppBootloader(): bool
    {
        return true;
    }

    public function hasRoutesBootloader(): bool
    {
        return true;
    }

    protected function getDefaultReadme(): array
    {
        return \array_merge(parent::getDefaultReadme(), [
            Section::Usage->value => [
                new FileBlock(__DIR__ . '/readme/usage.md'),
            ],
        ]);
    }
}
