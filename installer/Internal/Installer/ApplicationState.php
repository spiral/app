<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Installer\Application\ComposerPackages;
use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Configurator\ResourceQueue;
use Installer\Internal\Events\PackageRegistered;
use Installer\Internal\EventStorage;
use Installer\Internal\Package;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\QuestionInterface;

final class ApplicationState
{
    private ?ApplicationInterface $application = null;

    /** @var array<class-string<Package>, Package> */
    private array $installedPackages = [];

    public function __construct(
        string $applicationPath,
        private readonly ComposerFile $composer,
        private readonly ResourceQueue $resource,
        private readonly ?EventStorage $eventStorage = null,
    ) {
    }

    public function setApplication(ApplicationInterface $application, int $type): void
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

        $this->eventStorage?->addEvent(new PackageRegistered(
            $package->getName(),
            $package->getVersion(),
            $package->isDev()
        ));

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

    public function isPackageInstalled(ComposerPackages $package): bool
    {
        foreach ($this->installedPackages as $installedPackage) {
            if ($installedPackage->getName() === (new Package($package))->getName()) {
                return true;
            }
        }

        return false;
    }

    private function copyFiles(): \Generator
    {
        foreach ($this->application->getResources() as $source => $target) {
            yield from $this->resource->copy(
                $source,
                $target
            );
        }

        foreach ($this->installedPackages as $package) {
            // Package resources

            foreach ($package->getResources() as $source => $target) {
                yield from $this->resource->copy(
                    \rtrim($package->getResourcesPath(), '/') . '/' . \ltrim($source, '/'),
                    $target
                );
            }
        }
    }
}
