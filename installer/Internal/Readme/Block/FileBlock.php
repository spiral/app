<?php

declare(strict_types=1);

namespace Installer\Internal\Readme\Block;

final class FileBlock implements \Stringable
{
    public function __construct(
        private readonly string $path,
    ) {
    }

    public function __toString(): string
    {
        $content = \file_get_contents($this->path);
        if ($content === false) {
            throw new \RuntimeException(\sprintf('Could not read file "%s".', $this->path));
        }

        return $content . "\n\n";
    }
}
