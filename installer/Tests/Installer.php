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
use Installer\Internal\Installer\ComposerStorageInterface;
use Installer\Module\RoadRunnerBridge\Question;
use Seld\JsonLint\ParsingException;
use Spiral\Files\FilesInterface;

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
        $appPath = $this->appPath . '/' . (string)$this;
        $buffer = new BufferIO();
        $composer = new Composer();
        $composerJson = new JsonFile(__DIR__ . '/Fixtures/composer.json');
        $composer->setPackage(new RootPackage('spiral/app', '1.0.0', '1.0.0'));

        $storage = new class($buffer, $composerJson) implements ComposerStorageInterface {
            private array $data;

            public function __construct(
                private readonly BufferIO $buffer,
                JsonFile $composerJson,
            ) {
                $this->data = $composerJson->read();
            }

            public function read(): array
            {
                return $this->data;
            }

            public function write(array $data): void
            {
                $this->data = $data;
                $this->buffer->write(\json_encode($data, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES));
            }
        };

        $installer = new \Installer\Internal\Installer(
            new IO($buffer),
            $composer,
            $appPath,
            $this->interactions,
            $storage
        );

        $installer->run();

        $configurator = new Configurator(
            new IO($buffer),
            $composer,
            $appPath,
            new class($buffer) implements FilesInterface {
                public function __construct(
                    private readonly BufferIO $buffer,
                ) {
                }

                public function ensureDirectory(string $directory, int $mode = null): bool
                {
                    return true;
                }

                public function read(string $filename): string
                {
                    // TODO: Implement read() method.
                }

                public function write(
                    string $filename,
                    string $data,
                    int $mode = null,
                    bool $ensureDirectory = false
                ): bool {
                    $this->buffer->write(\sprintf('Writing to %s', $filename));
                    $this->buffer->write($data);

                    return true;
                }

                public function append(
                    string $filename,
                    string $data,
                    int $mode = null,
                    bool $ensureDirectory = false
                ): bool {
                    $this->buffer->write(\sprintf('Appending to %s', $filename));
                    $this->buffer->write($data);

                    return true;
                }

                public function delete(string $filename): bool
                {
                    $this->buffer->write(\sprintf('Deleting %s', $filename));

                    return true;
                }

                public function deleteDirectory(string $directory, bool $contentOnly = false): bool
                {
                    $this->buffer->write(\sprintf('Deleting directory %s', $directory));

                    return true;
                }

                public function move(string $filename, string $destination): bool
                {
                    $this->buffer->write(\sprintf('Moving %s to %s', $filename, $destination));

                    return true;
                }

                public function copy(string $filename, string $destination): bool
                {
                    $this->buffer->write(\sprintf('Copying %s to %s', $filename, $destination));

                    return true;
                }

                public function touch(string $filename, int $mode = null): bool
                {
                    $this->buffer->write(\sprintf('Touching %s', $filename));

                    return true;
                }

                public function exists(string $filename): bool
                {
                    return true;
                }

                public function size(string $filename): int
                {
                    return 1;
                }

                public function extension(string $filename): string
                {
                    // TODO: Implement extension() method.
                }

                public function md5(string $filename): string
                {
                    // TODO: Implement md5() method.
                }

                public function time(string $filename): int
                {
                    // TODO: Implement time() method.
                }

                public function isDirectory(string $filename): bool
                {
                    return true;
                }

                public function isFile(string $filename): bool
                {
                    return true;
                }

                public function getPermissions(string $filename): int
                {
                    // TODO: Implement getPermissions() method.
                }

                public function setPermissions(string $filename, int $mode): bool
                {
                    // TODO: Implement setPermissions() method.
                }

                public function getFiles(string $location, string $pattern = null): array
                {
                    // TODO: Implement getFiles() method.
                }

                public function tempFilename(string $extension = '', string $location = null): string
                {
                    // TODO: Implement tempFilename() method.
                }

                public function normalizePath(string $path, bool $asDirectory = false): string
                {
                    // TODO: Implement normalizePath() method.
                }

                public function relativePath(string $path, string $from): string
                {
                    // TODO: Implement relativePath() method.
                }
            },
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
