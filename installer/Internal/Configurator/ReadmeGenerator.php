<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Installer\Internal\ApplicationInterface;
use Installer\Internal\Package;
use Installer\Internal\Question\Option\Option;
use Installer\Internal\Question\QuestionInterface;

final class ReadmeGenerator
{
    public function __construct(
        private readonly string $filePath,
        private readonly ApplicationInterface $application,
    ) {
    }

    public function generate(): void
    {
        if (!\file_exists($this->filePath)) {
            return;
        }

        $content = \file_get_contents($this->filePath);
        $content = \str_replace(':app_name', $this->application->getName(), $content);
        $content = \str_replace(':date', (new \DateTime())->format('r'), $content);


        $nextSteps = ['## Configuration'];

        foreach ($this->generateForApplication() as $line) {
            $nextSteps[] = $line;
        }

        // from required packages
        foreach ($this->application->getPackages() as $package) {
            foreach ($this->generateForPackage($package) as $line) {
                $nextSteps[] = $line;
            }
        }

        // from installed optional packages
        foreach ($this->application->getQuestions() as $question) {
            foreach ($this->generateForQuestion($question) as $line) {
                $nextSteps[] = $line;
            }
        }

        $content = \str_replace(
            ':next_steps',
            \implode(\PHP_EOL, $nextSteps),
            $content
        );

        \file_put_contents($this->filePath, $content);
    }

    public function generateForApplication(): \Traversable
    {
        foreach ($this->application->getInstructions() as $index => $instruction) {
            yield \sprintf('%d. %s', (int)$index + 1, \strip_tags($instruction));
        }
    }

    public function generateForPackage(Package $package): \Traversable
    {
        if ($package->getInstructions() === []) {
            return;
        }

        yield \sprintf('### %s', $package->getTitle());

        foreach ($package->getInstructions() as $index => $instruction) {
            yield \sprintf('%s. %s', (int)$index + 1, \strip_tags($instruction));
        }
    }

    public function generateForQuestion(QuestionInterface $question): \Traversable
    {
        foreach ($question->getOptions() as $option) {
            foreach ($option instanceof Option ? $option->getPackages() : [] as $package) {
                if ($this->application->isPackageInstalled($package)) {
                    yield from $this->generateForPackage($package);
                }
            }
        }
    }
}
