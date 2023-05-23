<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Composer\Json\JsonFile;
use Composer\Package\BasePackage;
use Composer\Package\Link;
use Composer\Package\RootPackageInterface;
use Composer\Package\Version\VersionParser;
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
    private array $composerDefinition = [];

    /**
     * @throws ParsingException
     */
    public function __construct(
        private readonly JsonFile $jsonFile,
        private readonly RootPackageInterface $package,
    ) {
        $this->composerDefinition = $this->jsonFile->read();
    }

    public function setApplicationType(int $type): void
    {
        $this->composerDefinition['extra']['spiral']['application-type'] = $type;
    }

    public function getApplicationType(): ?int
    {
        return $this->composerDefinition['extra']['spiral']['application-type'] ?? null;
    }

    public function addQuestionAnswer(QuestionInterface $question, BooleanOption $answer): void
    {
        // Add option to the extra section
        if (!\array_key_exists($question::class, $this->composerDefinition['extra']['spiral']['options'] ?? [])) {
            $this->composerDefinition['extra']['spiral']['options'][$question::class] = $answer->value;
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
        if (!\in_array($package->getName(), $this->composerDefinition['extra']['spiral']['packages'] ?? [], true)) {
            $this->composerDefinition['extra']['spiral']['packages'][] = $package->getName();
        }
    }

    /**
     * @return string[]
     */
    public function getInstalledPackages(): array
    {
        return $this->composerDefinition['extra']['spiral']['packages'] ?? [];
    }

    /**
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
        $this->package->setExtra($this->composerDefinition['extra'] ?? []);

        yield 'Storing composer.json ...';

        $this->composerDefinition['autoload'] = $autoload;
        $this->composerDefinition['autoload-dev'] = $autoloadDev;
        $this->composerDefinition['autoload']['psr-4']['Installer\\'] = 'installer';

        $this->jsonFile->write($this->composerDefinition);

        yield 'composer.json file updated.';
    }

    /**
     * @throws \Exception
     */
    public function removeInstaller(array $autoload, array $autoloadDev): \Generator
    {
        yield 'Removing Configurator from composer.json ...';

        unset(
            $this->composerDefinition['scripts']['post-install-cmd'],
            $this->composerDefinition['scripts']['post-update-cmd'],
            $this->composerDefinition['extra']['spiral']
        );

        $this->composerDefinition['autoload'] = $autoload;
        $this->composerDefinition['autoload-dev'] = $autoloadDev;

        $this->jsonFile->write($this->composerDefinition);
    }

    private function removeInstallerFromDefinition(): \Generator
    {
        yield 'Removing Installer from composer.json ...';

        unset(
            $this->composerDevRequires['composer/composer'],
            $this->composerDefinition['require-dev']['composer/composer'],
            $this->composerDefinition['scripts']['pre-update-cmd'],
            $this->composerDefinition['scripts']['pre-install-cmd'],
        );
    }
}
