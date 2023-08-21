<?php

declare(strict_types=1);

namespace Tests;

use Installer\Internal\ClassMetadataInterface;

final class ManualClassMetadata implements ClassMetadataInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $path,
        public readonly string $namespace
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }
}
