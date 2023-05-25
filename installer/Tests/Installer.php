<?php

declare(strict_types=1);

namespace Tests;

use Composer\Composer;
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

final class Installer implements \Stringable
{
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

        $buffer = new BufferIO();
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

        return new InstallationResult(
            $files,
            $appPath,
            $buffer->getOutput(),
            $this->eventStorage->getEvents(),
        );
    }

    public function __toString(): string
    {
        return 'installer-' . $this->interactions->requestApplicationType() . '-' . \md5(\microtime());
    }
}
