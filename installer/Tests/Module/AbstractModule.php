<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Package;

abstract class AbstractModule implements ModuleInterface
{
    public function __construct(
        protected readonly Package $package
    ) {
    }

    public function getPackage(): string
    {
        return $this->package->getName();
    }

    public function getGenerators(): array
    {
        return \array_map(
            static fn (string|GeneratorInterface $generator): string => \is_string($generator)
                ? $generator
                : $generator::class,
            $this->package->getGenerators()
        );
    }

    public function getBootloaders(): array
    {
        return [];
    }

    public function getCopiedResources(): array
    {
        return $this->package->getResources();
    }

    public function getRemovedResources(): array
    {
        return [];
    }

    public function getMiddleware(): array
    {
        return [];
    }

    public function getEnvironmentVariables(): array
    {
        return [];
    }

    public function getResourcesPath(): string
    {
        return $this->package->getResourcesPath();
    }
}
