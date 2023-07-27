<?php

declare(strict_types=1);

namespace Tests;

use Composer\Composer;
use Composer\Console\Application;
use Composer\IO\BufferIO;
use Composer\Package\RootPackage;
use Installer\Application\ApplicationSkeleton;
use Installer\Internal\Config;
use Installer\Internal\Configurator;
use Installer\Internal\Console\IO;
use Installer\Internal\EventStorage;
use Installer\Module\RoadRunnerBridge\Question;
use Seld\JsonLint\ParsingException;
use Spiral\Files\Files;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\StreamOutput;
use Tests\Module\AbstractModule;

final class Installer implements \Stringable
{
    private string $applicationUniqueHash;
    private array $modules = [];

    public static function create(
        Config $config,
        string $applicationClass,
        string $appPath,
    ): self {
        return new self(
            new FakeInteractions($applicationClass, $config),
            new EventStorage(),
            $appPath
        );
    }

    private function __construct(
        private readonly FakeInteractions $interactions,
        private readonly EventStorage $eventStorage,
        private readonly string $appPath,
    ) {
        $this->applicationUniqueHash = \md5(\microtime());
    }

    public function withRoadRunner(): self
    {
        $this->addAnswer(Question::class, true);

        return $this;
    }

    public function withSkeleton(): self
    {
        $this->addAnswer(ApplicationSkeleton::class, true);

        return $this;
    }

    public function addAnswer(string $question, int|string|bool $answer): self
    {
        $this->interactions->addAnswer($question, $answer);

        return $this;
    }

    public function addModule(AbstractModule $module): self
    {
        $this->modules[] = $module;

        return $this;
    }

    /**
     * @throws ParsingException
     */
    public function run(): InstallationResult
    {
        $files = new Files();

        $appPath = $this->appPath . '/' . $this;
        $composerJson = $appPath . '/composer.json';

        $files->ensureDirectory($appPath);
        $files->copy(__DIR__ . '/Fixtures/composer.json', $composerJson);

        $buffer = new BufferIO(verbosity: StreamOutput::VERBOSITY_VERBOSE);
        $composer = new Composer();

        $composer->setPackage(new RootPackage('spiral/app', '1.0.0', '1.0.0'));

        $installer = new \Installer\Internal\Installer(
            io: new IO($buffer),
            composerFilePath: $composerJson,
            composer: $composer,
            projectRoot: $appPath,
            interactions: $this->interactions,
            eventStorage: $this->eventStorage,
        );

        $installer->run();

        if ((bool) \getenv('RUN_APPLICATION_TESTS')) {
            $this->installDependencies($appPath);
        }

        $configurator = new Configurator(
            io: new IO($buffer),
            composerFilePath: $composerJson,
            composer: $composer,
            classMetadata: new FakeClassMetadataRepository($appPath),
            projectRoot: $appPath,
            files: new FakeFileSystem($buffer, new Files()),
            eventStorage: $this->eventStorage,
        );

        $configurator->run();

        if ((bool) \getenv('RUN_APPLICATION_TESTS')) {
            $this->runPostCreateProjectScripts($appPath);
            $testsResult = $this->runTests($appPath);
        }

        return new InstallationResult(
            $files,
            (string) $this,
            $this->appPath,
            $buffer->getOutput(),
            $this->eventStorage->getEvents(),
            new InstallationModuleResult($this->modules),
            (new \ReflectionProperty($configurator, 'application'))->getValue($configurator),
            isset($testsResult)
                ? $testsResult === 0
                : null
        );
    }

    public function __toString(): string
    {
        return 'installer-' . $this->interactions->requestApplicationType() . '-' . $this->applicationUniqueHash;
    }

    private function installDependencies(string $appPath): void
    {
        $application = new Application();
        $application->setAutoExit(false);

        $application->run(new ArrayInput([
            'command' => 'install',
            '--working-dir' => $appPath,
            '--no-scripts',
            '--quiet'
        ]));
    }

    private function runPostCreateProjectScripts(string $appPath): void
    {
        $application = new Application();
        $application->setAutoExit(false);

        $application->run(new ArrayInput([
            'command' => 'run-script',
            'script' => 'post-create-project-cmd',
            '--working-dir' => $appPath,
            '--quiet'
        ]));
    }

    private function runTests(string $appPath): int
    {
        $application = new Application();
        $application->setAutoExit(false);

        return $application->run(new ArrayInput([
            'command' => 'run-script',
            'script' => 'test',
            '--working-dir' => $appPath,
            '-v'
        ]), new NullOutput());
    }
}
