<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Config;
use Installer\Internal\Console\IOInterface;
use Installer\Internal\Question\Option\OptionInterface;
use Installer\Internal\Question\QuestionInterface;

final class IOInteractions implements InteractionsInterface
{
    public function __construct(
        private readonly IOInterface $io,
        private readonly Config $config,
        private readonly array $composerDefinition,
    ) {
    }

    public function requestApplicationType(): int
    {
        $query = [
            \sprintf(
                "\n  <question>%s</question>\n",
                'Which application preset do you want to install?',
            ),
        ];

        foreach ($this->config->getApplications() as $key => $app) {
            if ($app instanceof ApplicationInterface) {
                $query[] = \sprintf("  [<comment>%s</comment>] %s\n", (int)$key + 1, $app->getName());
            }
        }

        $query[] = \sprintf('  Make your selection <comment>(default: %s)</comment>: ', 1);

        return (int)$this->io->ask(\implode($query), 1) - 1;
    }

    public function promptForOptionalPackages(ApplicationInterface $application): \Generator
    {
        foreach ($application->getQuestions() as $question) {
            if ($question->canAsk($this->composerDefinition)) {
                yield from $this->promptForOptionalPackage($question);
            }
        }
    }

    /**
     * @return \Generator<QuestionInterface, OptionInterface>
     */
    private function promptForOptionalPackage(QuestionInterface $question): \Generator
    {
        do {
            try {
                $answer = $this->askQuestion($question);

                if ($answer === 0) {
                    return;
                }

                if (!$question->hasOption($answer)) {
                    throw new \InvalidArgumentException('Invalid answer!');
                }
                break;
            } catch (\InvalidArgumentException $e) {
                $this->io->error($e->getMessage());
            }
        } while (true);

        yield $question => $question->getOption($answer);
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
