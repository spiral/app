<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

interface ComposerStorageInterface
{
    public function read(): array;

    public function write(array $data): void;
}
