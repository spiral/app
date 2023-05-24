<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Composer\Json\JsonFile;
use Composer\Package\BasePackage;
use Composer\Package\Link;
use Composer\Package\RootPackageInterface;
use Composer\Package\Version\VersionParser;
use Installer\Internal\Console\Output;
use Installer\Internal\Package;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\QuestionInterface;
use Seld\JsonLint\ParsingException;

final class ComposerFile
{
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
    ) {
        $this->definition = $this->storage->read();
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
        if ($package->isDev() || \in_array($package->getName(), $this->config['require-dev'] ?? [], true)) {
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
            default => null
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
            $this->definition['extra']['spiral']
        );

        $this->definition['autoload'] = $autoload;
        $this->definition['autoload-dev'] = $autoloadDev;

        $this->storage->write($this->definition);
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
