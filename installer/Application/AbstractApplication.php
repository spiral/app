<?php

declare(strict_types=1);

namespace Installer\Application;

use Composer\Package\PackageInterface;
use Installer\Package\Generator\GeneratorInterface;
use Installer\Package\Package;
use Installer\Package\Packages;
use Installer\Question\QuestionInterface;

/**
 * @psalm-import-type AutoloadRules from PackageInterface
 * @psalm-import-type DevAutoloadRules from PackageInterface
 */
abstract class AbstractApplication implements ApplicationInterface
{
    /**
     * @var Package[]
     */
    private array $packages = [];

    /**
     * @param Packages[] $packages
     * @param AutoloadRules $autoload
     * @param DevAutoloadRules $autoloadDev
     * @param QuestionInterface[] $questions
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        private readonly string $name,
        array $packages = [],
        private readonly array $autoload = [],
        private readonly array $autoloadDev = [],
        private readonly array $questions = [],
        private readonly array $resources = [],
        private readonly array $generators = [],
        private readonly array $commands = [
            'composer rr:download',
        ]
    ) {
        $this->setPackages($packages);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Package[]
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * @return QuestionInterface[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    /**
     * @return AutoloadRules
     */
    public function getAutoload(): array
    {
        return $this->autoload;
    }

    /**
     * @return DevAutoloadRules
     */
    public function getAutoloadDev(): array
    {
        return $this->autoloadDev;
    }

    public function getResources(): array
    {
        return $this->resources;
    }

    public function getGenerators(): \Generator
    {
        foreach ($this->generators as $generator) {
            yield null => $generator;
        }

        foreach ($this->getQuestions() as $question) {
            foreach ($question->getOptions() as $option) {
                foreach ($option->getPackages() as $package) {
                    foreach ($package->getGenerators() as $generator) {
                        yield $package => $generator;
                    }
                }
            }
        }
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @param Packages[] $packages
     */
    private function setPackages(array $packages): void
    {
        foreach ($packages as $package) {
            $this->packages[] = new Package($package);
        }
    }
}
