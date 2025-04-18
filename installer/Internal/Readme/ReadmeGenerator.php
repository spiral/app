<?php

declare(strict_types=1);

namespace Installer\Internal\Readme;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Events\ReadmeGenerated;
use Installer\Internal\EventStorage;
use Installer\Internal\Generator\Context;
use Installer\Internal\Package;
use Installer\Internal\Question\Option\Option;
use Installer\Internal\Question\QuestionInterface;
use Spiral\Files\FilesInterface;

final class ReadmeGenerator
{
    public function __construct(
        public readonly string $filePath,
        private readonly ApplicationInterface $application,
        private readonly Context $context,
        private readonly FilesInterface $files,
    ) {}

    public function generate(?EventStorage $eventStorage = null): void
    {
        if (!\file_exists($this->filePath)) {
            return;
        }

        $content = \file_get_contents($this->filePath);
        $content = \str_replace(':app_name', $this->application->getName(), $content);
        $content = \str_replace(':date', (new \DateTime())->format('r'), $content);

        $nextStepsContent = '';

        foreach ($this->generateForApplication() as $section => $line) {
            $nextSteps[$section][] = $line;
        }

        // from required packages
        foreach ($this->application->getPackages() as $package) {
            foreach ($this->generateForPackage($package) as $section => $line) {
                $nextSteps[$section][] = $line;
            }
        }

        // from installed optional packages
        foreach ($this->application->getQuestions() as $question) {
            foreach ($this->generateForQuestion($question) as $section => $line) {
                $nextSteps[$section][] = $line;
            }
        }

        foreach ($this->generateForContext() as $section => $line) {
            $nextSteps[$section][] = $line;
        }

        foreach (Section::cases() as $case) {
            if (!isset($nextSteps[$case->value]) || \count($nextSteps[$case->value]) === 0) {
                continue;
            }

            $nextStepsContent .= \sprintf("## %s\n\n", $case->value);
            foreach ($nextSteps[$case->value] as $instruction) {
                $nextStepsContent .= $instruction;
            }
        }

        $content = \str_replace(
            ':next_steps',
            \trim($nextStepsContent, "\n"),
            $content,
        );

        $this->files->write(
            $this->filePath,
            $content,
            FilesInterface::RUNTIME,
        );

        $eventStorage?->addEvent(new ReadmeGenerated($this->filePath, $content));
    }

    public function generateForApplication(): \Traversable
    {
        foreach ($this->application->getReadme() as $section => $instructions) {
            foreach ($instructions as $instruction) {
                yield $section => $instruction;
            }
        }
    }

    public function generateForPackage(Package $package): \Traversable
    {
        $readme = $package->getReadme();

        foreach ($readme as $section => $instructions) {
            foreach ($instructions as $instruction) {
                yield $section => $instruction;
            }
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

    private function generateForContext(): \Traversable
    {
        foreach ($this->context->readme as $section => $instructions) {
            foreach ($instructions as $instruction) {
                yield $section => $instruction;
            }
        }
    }
}
