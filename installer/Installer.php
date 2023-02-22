<?php

declare(strict_types=1);

namespace Installer;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Package\BasePackage;
use Composer\Package\Link;
use Composer\Package\RootPackageInterface;
use Composer\Package\Version\VersionParser;
use Composer\Script\Event;
use Installer\Application\ApplicationInterface;
use Installer\Package\Package;
use Installer\Question\Option\BooleanOption;
use Installer\Question\Option\Option;
use Installer\Question\QuestionInterface;
use Seld\JsonLint\ParsingException;

final class Installer extends AbstractInstaller
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private ApplicationInterface $application;
    private RootPackageInterface $rootPackage;

    /** @var Link[] */
    private array $composerRequires = [];

    /** @var Link[] */
    private array $composerDevRequires = [];

    /** @var array<string, BasePackage::STABILITY_*> */
    private array $stabilityFlags = [];

    private readonly bool $verbose;

    /**
     * @throws ParsingException
     */
    public function __construct(
        IOInterface $io,
        Composer $composer,
        ?string $projectRoot = null,
    ) {
        parent::__construct($io, $projectRoot);

        $this->rootPackage = $composer->getPackage();
        $this->composerRequires = $this->rootPackage->getRequires();
        $this->composerDevRequires = $this->rootPackage->getDevRequires();
        $this->stabilityFlags = $this->rootPackage->getStabilityFlags();
    }

    public static function install(Event $event): void
    {
        $installer = new self($event->getIO(), $event->getComposer());
        $installer->io->success('Setting up application preset...');

        $installer->io->info(
            <<<'WELCOME'
                        
   _____         _              _ 
  / ____|       (_)            | |
 | (___   _ __   _  _ __  __ _ | |
  \___ \ | '_ \ | || '__|/ _` || |
  ____) || |_) || || |  | (_| || |
 |_____/ | .__/ |_||_|   \__,_||_|
         | |                      
         |_|                      
WELCOME,
        );

        do {
            try {
                $type = $installer->requestApplicationType();
                $installer->setApplicationType($type);
                break;
            } catch (\InvalidArgumentException $e) {
                $installer->io->error($e->getMessage());
            }
        } while (true);

        $installer->io->success('Setting up required packages...');
        $installer->setRequiredPackages();

        $installer->io->success('Setting up optional packages...');
        $installer->promptForOptionalPackages();

        $installer->io->success('Setting up application files...');
        $installer->setApplicationFiles();

        $installer->removeInstallerFromDefinition();
        $installer->updateRootPackage();

        $installer->finalize();
    }

    private function setRequiredPackages(): void
    {
        foreach ($this->application->getPackages() as $package) {
            $this->addPackage($package);
        }
    }

    private function setApplicationFiles(): void
    {
        foreach ($this->application->getResources() as $source => $target) {
            $this->resource->copy($source, $target);
        }
    }

    private function promptForOptionalPackages(): void
    {
        foreach ($this->application->getQuestions() as $question) {
            if ($question->canAsk($this->composerDefinition)) {
                $this->promptForOptionalPackage($question);
            }
        }
    }

    private function promptForOptionalPackage(QuestionInterface $question): void
    {
        do {
            try {
                $answer = $this->askQuestion($question);

                if ($answer === 0) {
                    return;
                }

                if (!$question->hasOption($answer)) {
                    throw new \InvalidArgumentException('Invalid package!');
                }
                break;
            } catch (\InvalidArgumentException $e) {
                $this->io->error($e->getMessage());
            }
        } while (true);

        // Add packages to install
        $option = $question->getOption($answer);
        switch (true) {
            case $option instanceof Option:
                foreach ($option->getPackages() as $package) {
                    $this->addPackage($package);
                }
                break;
            case $option instanceof BooleanOption:
                $this->addBooleanAnswer($question, $option);
        }
    }

    private function requestApplicationType(): int
    {
        $query = [
            \sprintf(
                "\n  <question>%s</question>\n",
                'Which application preset do you want to install?',
            ),
        ];
        foreach ($this->config as $key => $app) {
            if ($app instanceof ApplicationInterface) {
                $query[] = \sprintf("  [<comment>%s</comment>] %s\n", (int)$key + 1, $app->getName());
            }
        }
        $query[] = \sprintf('  Make your selection <comment>(default: %s)</comment>: ', 1);

        return (int)$this->io->ask(\implode($query), 1) - 1;
    }

    private function setApplicationType(int $type): void
    {
        if (!isset($this->config[$type]) || !$this->config[$type] instanceof ApplicationInterface) {
            throw new \InvalidArgumentException('Invalid preset! Please select one of the available presets.');
        }

        $this->application = $this->config[$type];

        $this->composerDefinition['extra']['spiral']['application-type'] = $type;
    }

    private function askQuestion(QuestionInterface $question): int
    {
        do {
            $answer = $this->io->ask($question->getQuestion(), (string)$question->getDefault());

            if (!\in_array(\strtolower((string)$answer), ['?', 'h', 'help'])) {
                break;
            }

            if ($question->getHelp() === null) {
                $this->io->error('Help is not available for this question.');
            } else {
                $this->io->title('Help:');
                $this->io->comment($question->getHelp());
            }
        } while (true);

        // Handling "y", "Y", "n", "N"
        if (\strtolower((string)$answer) === 'n') {
            $answer = 0;
        }
        if (\strtolower((string)$answer) === 'y' && count($question->getOptions()) === 2) {
            $answer = 1;
        }

        if (!$question->hasOption((int)$answer)) {
            throw new \InvalidArgumentException('Invalid answer! Please select one of the available options.');
        }

        return (int)$answer;
    }

    private function addPackage(Package $package): void
    {
        $this->io->debug(
            \sprintf(
                '- Adding package <info>%s</info> (<comment>%s</comment>)',
                $package->getName(),
                $package->getVersion(),
            ),
        );

        $versionParser = new VersionParser();
        $constraint = $versionParser->parseConstraints($package->getVersion());

        $link = new Link('__root__', $package->getName(), $constraint, 'requires', $package->getVersion());

        /** @psalm-suppress PossiblyInvalidArgument */
        if (\in_array($package->getName(), $this->config['require-dev'] ?? [], true)) {
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

        // Package resources
        foreach ($package->getResources() as $source => $target) {
            $this->resource->copy($source, $target);
        }
    }

    private function updateRootPackage(): void
    {
        $autoload = $this->application->getAutoload();
        $autoload['psr-4']['Installer\\'] = 'installer';

        $this->rootPackage->setRequires($this->composerRequires);
        $this->rootPackage->setDevRequires($this->composerDevRequires);
        $this->rootPackage->setStabilityFlags($this->stabilityFlags);
        $this->rootPackage->setAutoload($autoload);
        $this->rootPackage->setDevAutoload($this->application->getAutoloadDev());
        $this->rootPackage->setExtra($this->composerDefinition['extra'] ?? []);
    }

    private function removeInstallerFromDefinition(): void
    {
        $this->io->success('Removing Installer from composer.json ...');

        unset(
            $this->composerDevRequires['composer/composer'],
            $this->composerDefinition['require-dev']['composer/composer'],
            $this->composerDefinition['scripts']['pre-update-cmd'],
            $this->composerDefinition['scripts']['pre-install-cmd'],
        );
    }

    private function finalize(): void
    {
        $this->composerDefinition['autoload'] = $this->application->getAutoload();
        $this->composerDefinition['autoload-dev'] = $this->application->getAutoloadDev();
        $this->composerDefinition['autoload']['psr-4']['Installer\\'] = 'installer';

        $this->composerJson->write($this->composerDefinition);
    }

    private function addBooleanAnswer(QuestionInterface $question, BooleanOption $answer): void
    {
        // Add option to the extra section
        if (!\array_key_exists($question::class, $this->composerDefinition['extra']['spiral']['options'] ?? [])) {
            $this->composerDefinition['extra']['spiral']['options'][$question::class] = $answer->value;
        }
    }
}
