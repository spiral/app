<?php

declare(strict_types=1);

namespace Installer\Application;

use Composer\Package\PackageInterface;
use Installer\Generator\GeneratorInterface;
use Installer\Package\Package;
use Installer\Question\Option\BooleanOption;
use Installer\Question\Option\Option;
use Installer\Question\QuestionInterface;

/**
 * @psalm-import-type AutoloadRules from PackageInterface
 * @psalm-import-type DevAutoloadRules from PackageInterface
 */
abstract class AbstractApplication implements ApplicationInterface
{
    /**
     * @var Package[]
     */
    private array $packages = [];

    /**
     * @var array<non-empty-string, bool>
     */
    private array $roadRunnerPlugins = [];

    private array $installedPackages = [];
    private array $options = [];

    /**
     * @param Package[] $packages
     * @param AutoloadRules $autoload
     * @param DevAutoloadRules $autoloadDev
     * @param QuestionInterface[] $questions
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        private readonly string $name,
        array $packages = [],
        private readonly array $autoload = [],
        private readonly array $autoloadDev = [],
        private readonly array $questions = [],
        private readonly array $resources = [],
        private readonly array $generators = [],
        private readonly array $commands = []
    ) {
        $this->setPackages($packages);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Package[]
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * @return QuestionInterface[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    /**
     * @return AutoloadRules
     */
    public function getAutoload(): array
    {
        return $this->autoload;
    }

    /**
     * @return DevAutoloadRules
     */
    public function getAutoloadDev(): array
    {
        return $this->autoloadDev;
    }

    public function getResources(): array
    {
        return $this->resources;
    }

    public function getGenerators(): \Generator
    {
        // application generators
        foreach ($this->generators as $generator) {
            yield null => $generator;
        }

        // required packages generators
        foreach ($this->getPackages() as $package) {
            foreach ($package->getGenerators() as $generator) {
                yield $package => $generator;
            }
        }

        // optional packages generators
        foreach ($this->getQuestions() as $question) {
            foreach ($question->getOptions() as $option) {
                foreach ($option instanceof Option ? $option->getPackages() : [] as $package) {
                    if ($this->isPackageInstalled($package)) {
                        foreach ($package->getGenerators() as $generator) {
                            yield $package => $generator;
                        }
                    }
                }
                if ($option instanceof BooleanOption) {
                    if ($this->getOption($question::class) === true) {
                        foreach ($option->generators as $generator) {
                            yield $question => $generator;
                        }
                    }
                }
            }
        }
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @param non-empty-string ...$name
     */
    public function useRoadRunnerPlugin(string ...$name): void
    {
        foreach ($name as $n) {
            $this->roadRunnerPlugins[$n] = true;
        }
    }

    /**
     * @return list<non-empty-string>
     */
    public function getRoadRunnerPlugins(): array
    {
        return \array_keys($this->roadRunnerPlugins);
    }

    public function isPackageInstalled(Package $package): bool
    {
        return \in_array($package->getName(), $this->installedPackages, true);
    }

    /**
     * @param class-string $question
     */
    public function getOption(string $question): mixed
    {
        return $this->options[$question] ?? null;
    }

    /**
     * @internal
     *
     * Don't use this method. It is called only once by the Installer
     */
    public function setInstalled(array $extra): void
    {
        $this->installedPackages = $extra['packages'] ?? [];
        $this->options = $extra['options'] ?? [];
    }

    /**
     * @param Package[] $packages
     */
    private function setPackages(array $packages): void
    {
        foreach ($packages as $package) {
            $this->packages[] = $package;
        }
    }
}
