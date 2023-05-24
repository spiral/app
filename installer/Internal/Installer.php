<?php

declare(strict_types=1);

namespace Installer\Internal;

use Composer\Composer;
use Composer\Json\JsonFile;
use Composer\Script\Event;
use Installer\Internal\Console\IO;
use Installer\Internal\Console\IOInterface;
use Installer\Internal\Installer\AbstractInstaller;
use Installer\Internal\Installer\ApplicationState;
use Installer\Internal\Installer\ComposerFile;
use Installer\Internal\Installer\ComposerStorage;
use Installer\Internal\Installer\InteractionsInterface;
use Installer\Internal\Installer\IOInteractions;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\Option\Option;
use Seld\JsonLint\ParsingException;

final class Installer extends AbstractInstaller
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private ApplicationInterface $application;
    private readonly InteractionsInterface $interactions;

    /**
     * @throws ParsingException
     * @throws \Exception
     */
    public static function install(Event $event): void
    {
        $installer = new self(
            new IO($event->getIO()),
            $event->getComposer()
        );

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
                $type = $installer->interactions->requestApplicationType();
                $installer->setApplicationType($type);
                break;
            } catch (\InvalidArgumentException $e) {
                $installer->io->error($e->getMessage());
            }
        } while (true);

        $installer->io->success('Setting up required packages...');
        $installer->io->success('Setting up optional packages...');

        $installer->promptForOptionalPackages();

        foreach ($installer->applicationState->persist() as $message) {
            $installer->io->success($message);
        }
    }

    /**
     * @throws ParsingException
     */
    public function __construct(
        IOInterface $io,
        Composer $composer,
        ?string $projectRoot = null,
        ?InteractionsInterface $interactions = null,
    ) {
        parent::__construct($io, $projectRoot);

        $this->applicationState = new ApplicationState(
            $this->projectRoot,
            $composerFile = new ComposerFile(
                new ComposerStorage(new JsonFile($this->composerFile)),
                $composer->getPackage()
            )
        );

        $this->interactions = $interactions ?? new IOInteractions(
            $this->io,
            $this->config,
            $composerFile->getDefinition()
        );
    }

    private function promptForOptionalPackages(): void
    {
        foreach ($this->interactions->promptForOptionalPackages($this->application) as $question => $option) {
            switch (true) {
                case $option instanceof Option:
                    foreach ($option->getPackages() as $package) {
                        $this->applicationState->addPackage($package);
                    }
                    break;
                case $option instanceof BooleanOption:
                    $this->applicationState->addBooleanAnswer($question, $option);
            }
        }
    }

    private function setApplicationType(int $type): void
    {
        if (!isset($this->config[$type]) || !$this->config[$type] instanceof ApplicationInterface) {
            throw new \InvalidArgumentException('Invalid preset! Please select one of the available presets.');
        }

        $this->application = $this->config[$type];
        $this->applicationState->setApplication($this->application, $type);
    }
}
