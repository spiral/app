<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Composer\Json\JsonFile;

final class ComposerStorage implements ComposerStorageInterface
{
    public function __construct(
        private readonly JsonFile $file,
    ) {
    }

    public function read(): array
    {
        return $this->file->read();
    }

    public function write(array $data): void
    {
        $this->file->write($data);
    }
}
