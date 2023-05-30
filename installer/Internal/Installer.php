<?php

declare(strict_types=1);

namespace Installer\Internal;

use Composer\Composer;
use Composer\Factory;
use Composer\Json\JsonFile;
use Composer\Script\Event;
use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Configurator\ResourceQueue;
use Installer\Internal\Console\IO;
use Installer\Internal\Console\IOInterface;
use Installer\Internal\Console\Output;
use Installer\Internal\Events\CopyEvent;
use Installer\Internal\Installer\AbstractInstaller;
use Installer\Internal\Installer\ApplicationState;
use Installer\Internal\Installer\ComposerFile;
use Installer\Internal\Installer\ComposerStorage;
use Installer\Internal\Installer\ComposerStorageInterface;
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
     */
    public function __construct(
        IOInterface $io,
        string $composerFilePath,
        Composer $composer,
        ?string $projectRoot = null,
        ?InteractionsInterface $interactions = null,
        ?ComposerStorageInterface $composerStorage = null,
        private readonly ?EventStorage $eventStorage = null,
    ) {
        parent::__construct($io, $composerFilePath, $projectRoot);

        $this->applicationState = new ApplicationState(
            $this->projectRoot,
            $composerFile = new ComposerFile(
                $composerStorage ?? new ComposerStorage(new JsonFile($this->composerFile)),
                $composer->getPackage(),
                $this->config,
            ),
            new ResourceQueue(directoriesMap: $this->config->getDirectories()),
            $this->eventStorage,
        );

        $this->interactions = $interactions ?? new IOInteractions(
            $this->io,
            $this->config,
            $composerFile->getDefinition()
        );
    }

    /**
     * @throws ParsingException
     * @throws \Exception
     */
    public static function install(Event $event, ?InteractionsInterface $interactions = null): void
    {
        $installer = new self(
            new IO($event->getIO()),
            Factory::getComposerFile(),
            $event->getComposer(),
            $interactions
        );

        $installer->run();
    }

    /**
     * @throws \Exception
     * @internal
     */
    public function run(): void
    {
        $this->io->success('Setting up application preset...');

        $this->io->info(
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
                $type = $this->interactions->requestApplicationType();
                $this->setApplicationType($type);
                break;
            } catch (\InvalidArgumentException $e) {
                $this->io->error($e->getMessage());
            }
        } while (true);

        $this->io->success('Setting up required packages...');
        $this->io->success('Setting up optional packages...');

        $this->promptForOptionalPackages();

        foreach ($this->applicationState->persist() as $output) {
            $this->eventStorage?->addEvent($output);

            if ($output instanceof Output) {
                if ($this->io->isVerbose()) {
                    $output->send($this->io);
                }
            } elseif ($output instanceof CopyEvent) {
                foreach ($this->resource->copy($output->getFullSource(), $output->getFullDestination()) as $copyTask) {
                    $this->eventStorage?->addEvent($copyTask);
                    if ($this->io->isVerbose()) {
                        $this->io->write((string)$copyTask);
                    }
                }
            } else {
                throw new \LogicException('Invalid output type!');
            }
        }
    }

    private function promptForOptionalPackages(): void
    {
        foreach ($this->interactions->promptForOptionalPackages($this->application) as $question => $option) {
            switch (true) {
                case $option instanceof Option:
                    foreach ($option->getPackages() as $package) {
                        $this->eventStorage?->addEvent($package);
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
        try {
            $this->application = $this->config->getApplication($type);

            $this->eventStorage?->addEvent($this->application);

            $this->applicationState->setApplication($this->application, $type);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException('Invalid preset! Please select one of the available presets.');
        }
    }
}
