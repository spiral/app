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
        private readonly ApplicationState $applicationState,
        private readonly Answers $answers = new Answers(),
    ) {}

    public function requestApplicationType(): int
    {
        if ($this->answers->application !== null) {
            return $this->presetApplicationType($this->answers->application);
        }

        $query = [
            \sprintf(
                "\n  <question>%s</question>\n",
                'Which application preset do you want to install?',
            ),
        ];

        foreach ($this->config->getApplications() as $key => $app) {
            if ($app instanceof ApplicationInterface) {
                $query[] = \sprintf("  [<comment>%s</comment>] %s\n", (int) $key + 1, $app->getName());
            }
        }

        $query[] = \sprintf('  Make your selection <comment>(default: %s)</comment>: ', 1);

        return (int) $this->io->ask(\implode($query), 1) - 1;
    }

    public function promptForOptionalPackages(ApplicationInterface $application): \Generator
    {
        $this->answers->assertKnown($application->getQuestions());

        foreach ($application->getQuestions() as $question) {
            if (!$question->canAsk($this->applicationState)) {
                continue;
            }

            $answer = $this->answers->get($question);
            yield from $answer === null
                ? $this->promptForOptionalPackage($question)
                : $this->presetAnswer($question, $answer);
        }
    }

    /**
     * @throws \RuntimeException
     */
    private function presetApplicationType(string $name): int
    {
        $names = [];
        foreach ($this->config->getApplications() as $key => $app) {
            if ($app instanceof ApplicationInterface) {
                if (\strtolower($app->getName()) === \strtolower($name)) {
                    $this->io->write(\sprintf('  Application preset: <comment>%s</comment>', $app->getName()));

                    return (int) $key;
                }

                $names[] = $app->getName();
            }
        }

        throw new \RuntimeException(\sprintf(
            '%s: unknown application "%s"; available: %s.',
            Answers::ENV,
            $name,
            \implode(', ', $names),
        ));
    }

    /**
     * @return \Generator<QuestionInterface, OptionInterface>
     *
     * @throws \RuntimeException
     */
    private function presetAnswer(QuestionInterface $question, int $answer): \Generator
    {
        if ($answer === 0) {
            $this->io->write(\sprintf('  %s: <comment>No</comment>', Answers::key($question)));

            return;
        }

        $question->hasOption($answer) or throw new \RuntimeException(\sprintf(
            '%s: the question "%s" has no option %d.',
            Answers::ENV,
            Answers::key($question),
            $answer,
        ));

        $option = $question->getOption($answer);
        $this->io->write(\sprintf('  %s: <comment>%s</comment>', Answers::key($question), $option->getName()));

        yield $question => $option;
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
            $answer = $this->io->ask($question->getQuestion(), (string) $question->getDefault());

            if (!\in_array(\strtolower((string) $answer), ['?', 'h', 'help'])) {
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
        if (\strtolower((string) $answer) === 'n') {
            $answer = 0;
        }
        if (\strtolower((string) $answer) === 'y' && \count($question->getOptions()) === 2) {
            $answer = 1;
        }

        if (!$question->hasOption((int) $answer)) {
            throw new \InvalidArgumentException('Invalid answer! Please select one of the available options.');
        }

        return (int) $answer;
    }
}
