<?php

declare(strict_types=1);

namespace Tests;

use Composer\IO\BufferIO;
use Composer\Json\JsonFile;
use Installer\Internal\Installer\ComposerStorageInterface;

final class FakeComposerStorage implements ComposerStorageInterface
{
    private array $data;

    public function __construct(
        private readonly BufferIO $buffer,
        JsonFile $composerJson,
        private readonly string $coposerPath,
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
        \file_put_contents($this->coposerPath, \json_encode($data, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES));
    }
}
