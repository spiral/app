<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Console\Output;
use Installer\Internal\Package;
use Installer\Internal\Question\Option\Option;
use Installer\Internal\Question\QuestionInterface;

final class InstallationInstructionRenderer
{
    public function __construct(
        private readonly ApplicationInterface $application,
    ) {
    }

    /**
     * @return \Generator<Output>
     */
    public function render(): \Generator
    {
        yield Output::success('Installation complete!');
        yield Output::write('');
        yield Output::comment('Next steps:');

        yield from $this->renderForApplication();

        foreach ($this->application->getPackages() as $package) {
            yield from $this->renderForPackage($package);
        }

        foreach ($this->application->getQuestions() as $question) {
            yield from $this->renderForQuestion($question);
        }
    }

    /**
     * @return \Generator<Output>
     */
    public function renderForApplication(): \Generator
    {
        foreach ($this->application->getInstructions() as $index => $instruction) {
            yield Output::write(\sprintf('  %s. %s', (int)$index + 1, $instruction));
        }
    }

    /**
     * @return \Generator<Output>
     */
    public function renderForPackage(Package $package): \Generator
    {
        if ($package->getInstructions() === []) {
            return;
        }

        yield Output::comment($package->getTitle());

        foreach ($package->getInstructions() as $index => $instruction) {
            yield Output::write(\sprintf('  %s. %s', (int)$index + 1, $instruction));
        }
    }

    /**
     * @return \Generator<Output>
     */
    public function renderForQuestion(QuestionInterface $question): \Generator
    {
        foreach ($question->getOptions() as $option) {
            foreach ($option instanceof Option ? $option->getPackages() : [] as $package) {
                if ($this->application->isPackageInstalled($package)) {
                    yield from $this->renderForPackage($package);
                }
            }
        }
    }
}
