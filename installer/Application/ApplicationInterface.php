<?php

declare(strict_types=1);

namespace Installer\Application;

use Installer\Package\Package;
use Installer\Question\QuestionInterface;

interface ApplicationInterface
{
    public function getName(): string;

    /**
     * @return Package[]
     */
    public function getPackages(): array;

    /**
     * @return QuestionInterface[]
     */
    public function getQuestions(): array;

    /**
     * @return array{
     *     psr-0?: array<string, string>,
     *     psr-4?: array<string, string>,
     *     classmap?: array<string>,
     *     files?: array<string>,
     *     exclude-from-classmap?: array<string>
     * }
     */
    public function getAutoload(): array;

    /**
     * @return array{
     *     psr-0?: array<string, string>,
     *     psr-4?: array<string, string>,
     *     classmap?: array<string>,
     *     files?: array<string>,
     *     exclude-from-classmap?: array<string>
     * }
     */
    public function getAutoloadDev(): array;

    public function getResources(): array;

    /**
     * @return class-string
     */
    public function getKernelClass(): string;
}
