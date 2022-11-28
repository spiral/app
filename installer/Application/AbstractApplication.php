<?php

declare(strict_types=1);

namespace Installer\Application;

use Installer\Package\Package;
use Installer\Package\Packages;
use Installer\Question\QuestionInterface;

abstract class AbstractApplication implements ApplicationInterface
{
    /**
     * @var Package[]
     */
    private array $packages;

    /**
     * @param Packages[] $packages
     * @param array{
     *     psr-0?: array<string, string>,
     *     psr-4?: array<string, string>,
     *     classmap?: array<string>,
     *     files?: array<string>,
     *     exclude-from-classmap?: array<string>
     * } $autoload
     * @param array{
     *     psr-0?: array<string, string>,
     *     psr-4?: array<string, string>,
     *     classmap?: array<string>,
     *     files?: array<string>,
     *     exclude-from-classmap?: array<string>
     * } $autoloadDev
     * @param QuestionInterface[] $questions
     */
    public function __construct(
        private readonly string $name,
        array $packages = [],
        private readonly array $autoload = [],
        private readonly array $autoloadDev = [],
        private readonly array $questions = [],
        private readonly array $resources = []
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
     * @return array{
     *     psr-0?: array<string, string>,
     *     psr-4?: array<string, string>,
     *     classmap?: array<string>,
     *     files?: array<string>,
     *     exclude-from-classmap?: array<string>
     * }
     */
    public function getAutoload(): array
    {
        return $this->autoload;
    }

    /**
     * @return array{
     *     psr-0?: array<string, string>,
     *     psr-4?: array<string, string>,
     *     classmap?: array<string>,
     *     files?: array<string>,
     *     exclude-from-classmap?: array<string>
     * }
     */
    public function getAutoloadDev(): array
    {
        return $this->autoloadDev;
    }

    public function getResources(): array
    {
        return $this->resources;
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
