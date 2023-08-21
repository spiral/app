<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Composer\Json\JsonFile;
use Installer\Internal\Events\ComposerUpdated;
use Installer\Internal\EventStorage;

final class ComposerStorage implements ComposerStorageInterface
{
    public function __construct(
        private readonly JsonFile $file,
        private readonly ?EventStorage $eventStorage = null,
    ) {
    }

    public function read(): array
    {
        return $this->file->read();
    }

    public function write(array $data): void
    {
        $this->file->write($data);
        $this->eventStorage?->addEvent(new ComposerUpdated($this->file->getPath(), $data));
    }
}
