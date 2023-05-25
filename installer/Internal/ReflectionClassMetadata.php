<?php

declare(strict_types=1);

namespace Installer\Internal;

final class ReflectionClassMetadata implements ClassMetadataInterface
{
    private readonly \ReflectionClass $reflection;

    public function __construct(
        string $class,
    ) {
        $this->reflection = new \ReflectionClass($class);
    }

    public function getName(): string
    {
        return $this->reflection->getName();
    }

    public function getPath(): string
    {
        return $this->reflection->getFileName();
    }

    public function getNamespace(): string
    {
        return $this->reflection->getNamespaceName();
    }
}
