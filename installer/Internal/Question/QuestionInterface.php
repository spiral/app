<?php

declare(strict_types=1);

namespace Installer\Internal\Question;

use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Package;
use Installer\Internal\Question\Option\OptionInterface;

interface QuestionInterface
{
    /**
     * Get formatted question with all options
     */
    public function getQuestion(): string;

    /**
     * @return \Generator<Package|null|QuestionInterface, GeneratorInterface|class-string<GeneratorInterface>>
     */
    public function getGenerators(): \Generator;

    public function getHelp(): ?string;

    public function isRequired(): bool;

    /**
     * @return OptionInterface[]
     */
    public function getOptions(): array;

    public function hasOption(int $key): bool;

    public function getOption(int $key): OptionInterface;

    public function getConditions(): array;

    public function getDefault(): int;

    public function canAsk(array $composerDefinition): bool;
}
