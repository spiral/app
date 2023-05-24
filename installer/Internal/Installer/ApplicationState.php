<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Installer\Internal\ApplicationInterface;
use Installer\Internal\Package;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\QuestionInterface;
use Installer\Internal\Resource;

final class ApplicationState
{
    private ?ApplicationInterface $application = null;

    /** @var array<class-string<Package>, Package> */
    private array $installedPackages = [];

    private Resource $resource;

    public function __construct(
        string $applicationPath,
        private readonly ComposerFile $composer,
    ) {
        $this->resource = new Resource($applicationPath);
    }

    public function setApplication(ApplicationInterface $application, int $type): \Generator
    {
        $this->application = $application;
        $this->composer->setApplicationType($type);

        foreach ($application->getPackages() as $package) {
            $this->addPackage($package);
        }
    }

    public function addBooleanAnswer(QuestionInterface $question, BooleanOption $answer): self
    {
        $this->composer->addQuestionAnswer($question, $answer);

        return $this;
    }

    public function addPackage(Package $package): void
    {
        // If package is already installed, skip it
        if (isset($this->installedPackages[$package::class])) {
            return;
        }

        $this->composer->addPackage($package);

        // Mark package as installed to prevent duplication of resources and dependencies
        $this->installedPackages[$package::class] = $package;

        // Register dependent packages
        foreach ($package->getDependencies() as $dependency) {
            $this->addPackage($dependency);
        }
    }

    /**
     * @return \Generator<string, string>
     * @throws \Exception
     */
    public function persist(): \Generator
    {
        yield from $this->copyFiles();

        yield from $this->composer->persist(
            $this->application->getAutoload(),
            $this->application->getAutoloadDev(),
        );
    }

    private function copyFiles(): \Generator
    {
        foreach ($this->application->getResources() as $source => $target) {
            $this->resource->copy(
                \rtrim($this->application->getResourcesPath(), '/') . '/' . \ltrim($source, '/'),
                $target
            );
            yield $source => $target;
        }

        foreach ($this->installedPackages as $package) {
            // Package resources

            foreach ($package->getResources() as $source => $target) {
                $this->resource->copy(
                    \rtrim($package->getResourcesPath() . '/') . \ltrim($source, '/'),
                    $target
                );

                yield $source => $target;
            }
        }
    }
}
