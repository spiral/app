<?php

declare(strict_types=1);

namespace Tests;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Config;
use Installer\Internal\Installer\InteractionsInterface;
use Installer\Internal\Question\Option\OptionInterface;
use Installer\Internal\Question\QuestionInterface;

final class FakeInteractions implements InteractionsInterface
{
    private readonly int $type;

    /*@var array<array-key, array{0: QuestionInterface, 1: OptionInterface}> */
    private array $answers = [];

    public function __construct(
        string $applicationClass,
        private readonly Config $config,
    ) {
        foreach ($config->getApplications() as $i => $application) {
            if ($application instanceof $applicationClass) {
                $this->type = $i;
                return;
            }
        }

        throw new \RuntimeException(\sprintf('Application [%s] not found', $applicationClass));
    }

    public function addAnswer(string $question, int|string|bool $answer): void
    {
        foreach ($this->config->getApplication($this->type)->getQuestions() as $q) {
            if ($q instanceof $question) {
                $question = $q;
                break;
            }
        }

        if (!$question instanceof QuestionInterface) {
            throw new \RuntimeException(\sprintf('Question [%s] not found', $question));
        }

        if ($answer === true) {
            $answer = 'Yes';
        } elseif ($answer === false) {
            $answer = 'No';
        }

        if (\is_int($answer)) {
            $answer = $question->getOption($answer);
        } else {
            foreach ($question->getOptions() as $option) {
                if ($option->getName() === $answer) {
                    $answer = $option;
                    break;
                }
            }
        }

        if (!$answer instanceof OptionInterface) {
            throw new \RuntimeException(\sprintf('Answer [%s] not found', $answer));
        }

        $this->answers[] = [$question, $answer];
    }

    public function requestApplicationType(): int
    {
        return $this->type;
    }

    public function promptForOptionalPackages(ApplicationInterface $application): \Generator
    {
        foreach ($this->answers as [$question, $answer]) {
            yield $question => $answer;
        }
    }
}
