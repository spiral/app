<?php

declare(strict_types=1);

namespace Installer\Internal;

use Composer\Package\PackageInterface;
use Installer\Application\ApplicationSkeleton;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\Option\Option;
use Installer\Internal\Question\QuestionInterface;

/**
 * @psalm-import-type AutoloadRules from PackageInterface
 * @psalm-import-type DevAutoloadRules from PackageInterface
 */
abstract class AbstractApplication implements ApplicationInterface
{
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
        private readonly array $packages = [],
        private readonly array $autoload = [],
        private readonly array $autoloadDev = [],
        private readonly array $questions = [],
        private readonly array $resources = [],
        private readonly array $generators = [],
        private readonly array $commands = [],
        private readonly array $instructions = []
    ) {
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
     * @return non-empty-string[]
     */
    public function getInstructions(): array
    {
        return $this->instructions === [] ? $this->getDefaultInstructions() : $this->instructions;
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

    public function getResourcesPath(): string
    {
        $path = __DIR__ . '/resources/';

        if (\is_dir($path)) {
            return $path;
        }

        return __DIR__;
    }

    public function getGenerators(): \Generator
    {
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

        // required packages generators
        foreach ($this->getPackages() as $package) {
            foreach ($package->getGenerators() as $generator) {
                yield $package => $generator;
            }
        }

        // application generators
        foreach ($this->generators as $generator) {
            yield $this => $generator;
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

    public function isRoadRunnerPluginRequired(string $plugin): bool
    {
        return \in_array($plugin, $this->roadRunnerPlugins, true);
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

    public function hasSkeleton(): bool
    {
        return $this->getOption(ApplicationSkeleton::class) === true;
    }

    /**
     * @internal
     *
     * Don't use this method. It is called only once by the Installer
     */
    public function setInstalled(array $installed): void
    {
        $this->installedPackages = $installed['packages'] ?? [];
        $this->options = $installed['options'] ?? [];
    }

    protected function getDefaultInstructions(): array
    {
        return [
            'Please, configure the environment variables in the <comment>.env</comment> file at the application\'s root.',
            'Read documentation about Spiral Framework: <comment>https://spiral.dev/docs</comment></info>',
        ];
    }
}
