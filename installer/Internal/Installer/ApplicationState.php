<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Composer\Package\BasePackage;
use Composer\Package\Link;
use Composer\Package\Version\VersionParser;
use Installer\Internal\ApplicationInterface;
use Installer\Internal\Package;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\QuestionInterface;
use Installer\Internal\Resource;

final class ApplicationState
{
    private ?ApplicationInterface $application = null;

    /** @var Link[] */
    private array $composerRequires = [];

    /** @var Link[] */
    private array $composerDevRequires = [];

    /** @var array<string, BasePackage::STABILITY_*> */
    private array $stabilityFlags = [];

    /**
     * @var array<class-string<Package>, Package>
     */
    private array $installedPackages = [];

    private array $composerDefinition = [];

    public function __construct(
        private readonly Resource $resource,
    ) {
    }

    public function setComposerDefinition(array $definition): self
    {
        $this->composerDefinition = $definition;

        return $this;
    }

    public function setApplication(ApplicationInterface $application): self
    {
        $this->application = $application;
        $this->composerDefinition['extra']['spiral']['application-type'] = $application->getName();

        return $this;
    }

    public function addBooleanAnswer(QuestionInterface $question, BooleanOption $answer): self
    {
        // Add option to the extra section
        if (!\array_key_exists($question::class, $this->composerDefinition['extra']['spiral']['options'] ?? [])) {
            $this->composerDefinition['extra']['spiral']['options'][$question::class] = $answer->value;
        }

        return $this;
    }

    public function addPackage(Package $package): self
    {
        // If package is already installed, skip it
        if (isset($this->installedPackages[$package::class])) {
            return $this;
        }

        $versionParser = new VersionParser();
        $constraint = $versionParser->parseConstraints($package->getVersion());

        $link = new Link('__root__', $package->getName(), $constraint, 'requires', $package->getVersion());

        /** @psalm-suppress PossiblyInvalidArgument */
        if ($package->isDev() || \in_array($package->getName(), $this->config['require-dev'] ?? [], true)) {
            unset(
                $this->composerDefinition['require'][$package->getName()],
                $this->composerRequires[$package->getName()],
            );

            $this->composerDefinition['require-dev'][$package->getName()] = $package->getVersion();
            $this->composerDevRequires[$package->getName()] = $link;
        } else {
            unset(
                $this->composerDefinition['require-dev'][$package->getName()],
                $this->composerDevRequires[$package->getName()],
            );

            $this->composerDefinition['require'][$package->getName()] = $package->getVersion();
            $this->composerRequires[$package->getName()] = $link;
        }

        $stability = match (VersionParser::parseStability($package->getVersion())) {
            'dev' => BasePackage::STABILITY_DEV,
            'alpha' => BasePackage::STABILITY_ALPHA,
            'beta' => BasePackage::STABILITY_BETA,
            'RC' => BasePackage::STABILITY_RC,
            default => null
        };

        if ($stability !== null) {
            $this->stabilityFlags[$package->getName()] = $stability;
        }

        // Add package to the extra section
        if (!\in_array($package, $this->composerDefinition['extra']['spiral']['packages'] ?? [], true)) {
            $this->composerDefinition['extra']['spiral']['packages'][] = $package->getName();
        }


        // Mark package as installed to prevent duplication of resources and dependencies
        $this->installedPackages[$package::class] = $package;

        // Register dependent packages
        foreach ($package->getDependencies() as $dependency) {
            $this->addPackage($dependency);
        }

        return $this;
    }

    public function copyFiles(): \Generator
    {
        $resource = $this->resource->withSource($this->application->getResourcesPath());
        foreach ($this->application->getResources() as $source => $target) {
            $resource->copy($source, $target);
            yield $source => $target;
        }

        foreach ($this->installedPackages as $package) {
            // Package resources

            $resource = $this->resource->withSource($package->getResourcesPath());
            foreach ($package->getResources() as $source => $target) {
                $resource->copy($source, $target);
                yield $source => $target;
            }
        }
    }

    public function getComposerRequires(): array
    {
        return $this->composerRequires;
    }

    public function getComposerDevRequires(): array
    {
        return $this->composerDevRequires;
    }

    public function getStabilityFlags(): array
    {
        return $this->stabilityFlags;
    }

    public function getInstalledPackages(): array
    {
        return $this->installedPackages;
    }
}
