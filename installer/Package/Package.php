<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\GeneratorInterface;

final class Package
{
    private string $name;
    private string $version;

    /**
     * @param array<GeneratorInterface|class-string<GeneratorInterface>> $generators
     */
    public function __construct(
        Packages $package,
        private readonly array $resources = [],
        private readonly array $generators = []
    ) {
        $this->setPackage($package);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getResources(): array
    {
        return $this->resources;
    }

    /**
     * @return array<GeneratorInterface|class-string<GeneratorInterface>>
     */
    public function getGenerators(): array
    {
        return $this->generators;
    }

    private function setPackage(Packages $package): void
    {
        $parts = \explode(':', $package->value);

        if (!isset($parts[0], $parts[1])) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid package name `%s`. The package name must follow this pattern: `%s`.',
                $package->value,
                'vendor/package-name:version'
            ));
        }

        $this->name = $parts[0];
        $this->version = $parts[1];
    }
}
