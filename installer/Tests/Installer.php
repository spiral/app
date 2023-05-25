<?php

declare(strict_types=1);

namespace Tests;

use Composer\Composer;
use Composer\IO\BufferIO;
use Composer\Json\JsonFile;
use Composer\Package\RootPackage;
use Installer\Application\ApplicationSkeleton;
use Installer\Internal\Config;
use Installer\Internal\Configurator;
use Installer\Internal\Console\IO;
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
            $appPath
        );
    }

    private function __construct(
        private readonly FakeInteractions $interactions,
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
        $appPath = $this->appPath . '/' . $this;
        $buffer = new BufferIO();
        $composer = new Composer();
        $composerJson = new JsonFile(__DIR__ . '/Fixtures/composer.json');
        $composer->setPackage(new RootPackage('spiral/app', '1.0.0', '1.0.0'));
        $storage = new FakeComposerStorage(
            $buffer,
            $composerJson,
            $appPath . '/composer.json',
        );

        $installer = new \Installer\Internal\Installer(
            new IO($buffer),
            $composer,
            $appPath,
            $this->interactions,
            $storage
        );

        $installer->run();

        $configurator = new Configurator(
            io: new IO($buffer),
            composer: $composer,
            classMetadata: new FakeClassMetadataRepository($appPath),
            projectRoot: $appPath,
            files: new FakeFileSystem($buffer, new Files()),
            composerStorage: $storage,
        );

        $configurator->run();

        return new InstallationResult(
            $appPath,
            $buffer->getOutput(),
        );
    }

    public function __toString(): string
    {
        return 'installer-' . $this->interactions->requestApplicationType() . '-' . \md5(\microtime());
    }
}
