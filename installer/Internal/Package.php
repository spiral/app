<?php

declare(strict_types=1);

namespace Installer\Internal;

use Installer\Application\ComposerPackages;
use Installer\Internal\Generator\GeneratorInterface;

class Package implements HasResourcesInterface
{
    private readonly bool $isDev;
    private readonly string $name;
    private readonly string $title;
    private readonly string $version;

    /**
     * @param array<GeneratorInterface|class-string<GeneratorInterface>> $generators
     * @param Package[] $dependencies
     */
    public function __construct(
        ComposerPackages $package,
        private readonly array $resources = [],
        private readonly array $generators = [],
        private readonly array $instructions = [],
        private readonly array $dependencies = [],
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

    public function isDev(): bool
    {
        return $this->isDev;
    }

    public function getResources(): array
    {
        return $this->resources;
    }

    public function getResourcesPath(): string
    {
        $dir = \dirname((new \ReflectionClass($this))->getFileName());

        $path = \rtrim($dir, '/') . '/resources/';

        if (\is_dir($path)) {
            return $path;
        }

        return $dir;
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

    /**
     * @return Package[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    private function setPackage(ComposerPackages $package): void
    {
        $string = $package->value;
        $this->isDev = \str_starts_with($string, 'dev:');

        if ($this->isDev) {
            $string = \substr($string, 4);
        }

        $parts = \explode(':', $string);

        if (!isset($parts[0], $parts[1])) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Invalid package name `%s`. The package name must follow this pattern: `%s`.',
                    $string,
                    'vendor/package-name:version'
                )
            );
        }

        $this->name = $parts[0];
        $this->title = $package->name;
        $this->version = $parts[1];
    }
}
