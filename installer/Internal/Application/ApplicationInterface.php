<?php

declare(strict_types=1);

namespace Installer\Internal\Application;

use Composer\Package\PackageInterface;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Package;
use Installer\Internal\Path;
use Installer\Internal\Question\QuestionInterface;

/**
 * @psalm-import-type AutoloadRules from PackageInterface
 * @psalm-import-type DevAutoloadRules from PackageInterface
 */
interface ApplicationInterface
{
    public function getName(): string;

    /**
     * @return Package[]
     */
    public function getPackages(): array;

    /**
     * @return QuestionInterface[]
     */
    public function getQuestions(): array;

    /**
     * @return AutoloadRules
     */
    public function getAutoload(): array;

    /**
     * @return DevAutoloadRules
     */
    public function getAutoloadDev(): array;

    public function getResources(): array;

    public function getResourcesPath(): Path;

    /**
     * @return array<non-empty-string, non-empty-string[]>
     */
    public function getReadme(): array;

    /**
     * @return \Generator<Package|null|QuestionInterface, GeneratorInterface|class-string<GeneratorInterface>>
     */
    public function getGenerators(): \Generator;

    /**
     * @return non-empty-string[]
     */
    public function getCommands(): array;

    /**
     * @param non-empty-string ...$name
     */
    public function useRoadRunnerPlugin(string ...$name): void;

    /**
     * @return list<non-empty-string>
     */
    public function getRoadRunnerPlugins(): array;

    /**
     * Check if RoadRunner plugin is required by application components.
     */
    public function isRoadRunnerPluginRequired(string $plugin): bool;

    public function isPackageInstalled(Package $package): bool;

    public function hasRoutesBootloader(): bool;

    public function hasAppBootloader(): bool;

    /**
     * @param class-string $question
     */
    public function getOption(string $question): mixed;

    /**
     * Check if user has selected application skeleton with demo data.
     */
    public function hasSkeleton(): bool;
}
