<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;

class Package
{
    private string $name;
    private string $title;
    private string $version;

    /**
     * @param array<GeneratorInterface|class-string<GeneratorInterface>> $generators
     */
    public function __construct(
        Packages $package,
        private readonly array $resources = [],
        private readonly array $generators = [],
        private readonly array $instructions = []
    ) {
        $this->setPackage($package);
    }

    /**
     * @return string Composer-like package name. i.e vendor/package
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string Human-readable package name
     */
    public function getTitle(): string
    {
        return $this->title;
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

    /**
     * @return non-empty-string[]
     */
    public function getInstructions(): array
    {
        return $this->instructions;
    }

    private function setPackage(Packages $package): void
    {
        $parts = \explode(':', $package->value);

        if (!isset($parts[0], $parts[1])) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Invalid package name `%s`. The package name must follow this pattern: `%s`.',
                    $package->value,
                    'vendor/package-name:version'
                )
            );
        }

        $this->name = $parts[0];
        $this->title = $package->name;
        $this->version = $parts[1];
    }
}
