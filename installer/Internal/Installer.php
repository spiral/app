<?php

declare(strict_types=1);

namespace Installer\Internal;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Script\Event;
use Installer\Internal\Installer\AbstractInstaller;
use Installer\Internal\Installer\ApplicationState;
use Installer\Internal\Installer\ComposerFile;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\Option\Option;
use Installer\Internal\Question\QuestionInterface;
use Seld\JsonLint\ParsingException;

final class Installer extends AbstractInstaller
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private ApplicationInterface $application;

    /**
     * @throws ParsingException
     */
    public function __construct(
        IOInterface $io,
        Composer $composer,
        ?string $projectRoot = null,
    ) {
        parent::__construct($io, $projectRoot);

        $this->applicationState = new ApplicationState(
            \realpath(__DIR__),
            $this->projectRoot,
            new ComposerFile(
                new JsonFile($this->composerFile),
                $composer->getPackage()
            )
        );
    }

    /**
     * @throws ParsingException
     * @throws \Exception
     */
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
        $installer->io->success('Setting up optional packages...');
        $installer->promptForOptionalPackages();

        foreach ($installer->applicationState->persist() as $message) {
            $installer->io->success($message);
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
                    $this->applicationState->addPackage($package);
                }
                break;
            case $option instanceof BooleanOption:
                $this->applicationState->addBooleanAnswer($question, $option);
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
        $this->applicationState->setApplication($this->application, $type);
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
}
