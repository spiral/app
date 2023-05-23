<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Installer\Internal\ApplicationInterface;
use Installer\Internal\Console\IO;
use Installer\Internal\Package;
use Installer\Internal\Question\Option\Option;
use Installer\Internal\Question\QuestionInterface;

final class InstallationInstructionRenderer
{
    public function __construct(
        private readonly IO $io,
        private readonly ApplicationInterface $application,
    ) {
    }

    public function render(): void
    {
        $this->io->info('Installation complete!');
        $this->io->write('');
        $this->io->comment('Next steps:');

        $this->renderForApplication();

        foreach ($this->application->getPackages() as $package) {
            $this->renderForPackage($package);
        }

        foreach ($this->application->getQuestions() as $question) {
            $this->renderForQuestion($question);
        }
    }

    public function renderForApplication(): void
    {
        foreach ($this->application->getInstructions() as $index => $instruction) {
            $this->io->write(\sprintf('  %s. %s', (int)$index + 1, $instruction));
        }
    }

    public function renderForPackage(Package $package): void
    {
        if ($package->getInstructions() === []) {
            return;
        }

        $this->io->comment($package->getTitle());

        foreach ($package->getInstructions() as $index => $instruction) {
            $this->io->write(\sprintf('  %s. %s', (int)$index + 1, $instruction));
        }
    }

    public function renderForQuestion(QuestionInterface $question): \Traversable
    {
        foreach ($question->getOptions() as $option) {
            foreach ($option instanceof Option ? $option->getPackages() : [] as $package) {
                if ($this->application->isPackageInstalled($package)) {
                    $this->renderForPackage($package);
                }
            }
        }
    }


}
