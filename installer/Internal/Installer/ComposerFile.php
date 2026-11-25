<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Composer\Package\BasePackage;
use Composer\Package\Link;
use Composer\Package\RootPackageInterface;
use Composer\Package\Version\VersionParser;
use Installer\Internal\Config;
use Installer\Internal\Console\Output;
use Installer\Internal\Package;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\QuestionInterface;

final class ComposerFile
{
    /**
     * Replaces the description of the installer in the composer.json of the application.
     */
    private const APPLICATION_DESCRIPTION = 'Spiral Skeleton Application';

    /** @var Link[] */
    private array $composerRequires = [];

    /** @var Link[] */
    private array $composerDevRequires = [];

    /** @var array<string, BasePackage::STABILITY_*> */
    private array $stabilityFlags = [];

    private array $definition;

    public function __construct(
        private readonly ComposerStorageInterface $storage,
        private readonly RootPackageInterface $package,
        private readonly Config $config,
    ) {
        $this->definition = $this->storage->read();
        $this->composerRequires = $this->package->getRequires();
        $this->composerDevRequires = $this->package->getDevRequires();
        $this->stabilityFlags = $this->package->getStabilityFlags();
    }

    public function getDefinition(): array
    {
        return $this->definition;
    }

    public function setApplicationType(int $type): void
    {
        $this->definition['extra']['spiral']['application-type'] = $type;
    }

    public function getApplicationType(): ?int
    {
        return $this->definition['extra']['spiral']['application-type'] ?? null;
    }

    public function addQuestionAnswer(QuestionInterface $question, BooleanOption $answer): void
    {
        // Add option to the extra section
        if (!\array_key_exists($question::class, $this->definition['extra']['spiral']['options'] ?? [])) {
            $this->definition['extra']['spiral']['options'][$question::class] = $answer->value;
        }
    }

    public function addPackage(Package $package): void
    {
        $versionParser = new VersionParser();
        $constraint = $versionParser->parseConstraints($package->getVersion());

        $link = new Link('__root__', $package->getName(), $constraint, 'requires', $package->getVersion());

        /** @psalm-suppress PossiblyInvalidArgument */
        if ($package->isDev() || $this->config->definedRequireDevPackage($package->getName())) {
            unset(
                $this->definition['require'][$package->getName()],
                $this->composerRequires[$package->getName()],
            );

            $this->definition['require-dev'][$package->getName()] = $package->getVersion();
            $this->composerDevRequires[$package->getName()] = $link;
        } else {
            unset(
                $this->definition['require-dev'][$package->getName()],
                $this->composerDevRequires[$package->getName()],
            );

            $this->definition['require'][$package->getName()] = $package->getVersion();
            $this->composerRequires[$package->getName()] = $link;
        }

        $stability = match (VersionParser::parseStability($package->getVersion())) {
            'dev' => BasePackage::STABILITY_DEV,
            'alpha' => BasePackage::STABILITY_ALPHA,
            'beta' => BasePackage::STABILITY_BETA,
            'RC' => BasePackage::STABILITY_RC,
            default => null,
        };

        if ($stability !== null) {
            $this->stabilityFlags[$package->getName()] = $stability;
        }

        // Add package to the extra section
        if (!\in_array($package->getName(), $this->definition['extra']['spiral']['packages'] ?? [], true)) {
            $this->definition['extra']['spiral']['packages'][] = $package->getName();
        }
    }

    /**
     * @return string[]
     */
    public function getInstalledPackages(): array
    {
        return $this->definition['extra']['spiral']['packages'] ?? [];
    }

    public function getInstalledOptions(): array
    {
        return $this->definition['extra']['spiral']['options'] ?? [];
    }

    /**
     * @return \Generator<Output>
     * @throws \Exception
     */
    public function persist(array $autoload, array $autoloadDev): \Generator
    {
        yield from $this->removeInstallerFromDefinition();

        $autoload['psr-4']['Installer\\'] = 'installer';

        $this->package->setRequires($this->composerRequires);
        $this->package->setDevRequires($this->composerDevRequires);
        $this->package->setStabilityFlags($this->stabilityFlags);
        $this->package->setAutoload($autoload);
        $this->package->setDevAutoload($autoloadDev);
        $this->package->setExtra($this->definition['extra'] ?? []);

        yield Output::comment('Storing composer.json ...');

        $this->definition['autoload'] = $autoload;
        $this->definition['autoload-dev'] = $autoloadDev;
        $this->definition['autoload']['psr-4']['Installer\\'] = 'installer';

        $this->storage->write($this->definition);

        yield Output::success('composer.json file updated.');
    }

    /**
     * @return \Generator<Output>
     * @throws \Exception
     */
    public function removeInstaller(array $autoload, array $autoloadDev): \Generator
    {
        yield Output::comment('Removing Configurator from composer.json ...');

        unset(
            $this->definition['scripts']['post-install-cmd'],
            $this->definition['scripts']['post-update-cmd'],
            $this->definition['extra']['spiral'],
        );

        $this->definition['description'] = self::APPLICATION_DESCRIPTION;
        $this->definition['autoload'] = $autoload;
        $this->definition['autoload-dev'] = $autoloadDev;

        foreach (['require', 'require-dev'] as $section) {
            if (isset($this->definition[$section])) {
                $this->definition[$section] = self::sortPackages($this->definition[$section]);
            }
        }

        $this->storage->write($this->definition);
    }

    /**
     * Orders packages as Composer does with `sort-packages`: php, extensions, libraries, then the rest.
     *
     * @param array<non-empty-string, string> $packages
     * @return array<non-empty-string, string>
     */
    private static function sortPackages(array $packages): array
    {
        $prefix = static fn(string $name): string => match (true) {
            $name === 'php' || \str_starts_with($name, 'php-') => '0-' . $name,
            \str_starts_with($name, 'ext-') => '2-' . $name,
            \str_starts_with($name, 'lib-') => '3-' . $name,
            default => '5-' . $name,
        };

        \uksort($packages, static fn(string $a, string $b): int => \strnatcmp($prefix($a), $prefix($b)));

        return $packages;
    }

    /**
     * @return \Generator<Output>
     */
    private function removeInstallerFromDefinition(): \Generator
    {
        yield Output::comment('Removing Installer from composer.json ...');

        unset(
            $this->composerDevRequires['composer/composer'],
            $this->definition['require-dev']['composer/composer'],
            $this->definition['scripts']['pre-update-cmd'],
            $this->definition['scripts']['pre-install-cmd'],
        );
    }
}
