<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Package;
use Installer\Package\Packages;
use Installer\Question\Option\Option;
use Installer\Question\Option\OptionInterface;

abstract class AbstractQuestion implements QuestionInterface
{
    protected const NONE_OPTION = 0;
    /**
     * @var array<int, OptionInterface>
     */
    private array $options = [];

    /**
     * @var array{require?: Package[], require-dev?: Package[]}
     */
    private array $conditions = [];

    /**
     * @param array<int, OptionInterface> $options
     * @param array{require?: Packages[], require-dev?: Packages[]} $conditions
     */
    public function __construct(
        private readonly string $question,
        private readonly bool $required,
        array $options,
        array $conditions = [],
        private readonly int $default = self::NONE_OPTION
    ) {
        $this->setOptions($options);
        $this->setConditions($conditions);
    }

    public function getHelp(): ?string
    {
        return null;
    }

    /**
     * Get formatted question with all options
     */
    public function getQuestion(): string
    {
        $ask = [
            \sprintf("\n  <question>%s</question>\n", $this->question),
        ];

        foreach ($this->options as $key => $option) {
            if ($key !== 0) {
                $ask[] = \sprintf("  [<comment>%d</comment>] %s\n", $key, $option->getName());
            }
        }

        if ($this->required !== true) {
            $ask[] = \sprintf("  [<comment>0</comment>] %s\n", $this->options[0]->getName());
        }

        if ($this->getHelp() !== null) {
            $ask[] = "  [<comment>?</comment>] Help\n";
        }

        $ask[] = \sprintf('  Make your selection <comment>(default: %s)</comment>: ', $this->default);

        return \implode($ask);
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return OptionInterface[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function hasOption(int $key): bool
    {
        return isset($this->options[$key]);
    }

    public function getOption(int $key): OptionInterface
    {
        return $this->hasOption($key) ? $this->options[$key] : throw new \InvalidArgumentException('Invalid option!');
    }

    /**
     * @return array{require?: Package[], require-dev?: Package[]}
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function getDefault(): int
    {
        return $this->default;
    }

    public function canAsk(array $composerDefinition): bool
    {
        if (isset($this->conditions['require'])) {
            foreach ($this->conditions['require'] as $package) {
                if (!\array_key_exists($package->getName(), $composerDefinition['require'])) {
                    return false;
                }
            }
        }

        if (isset($this->conditions['require-dev'])) {
            foreach ($this->conditions['require-dev'] as $package) {
                if (!\array_key_exists($package->getName(), $composerDefinition['require-dev'])) {
                    return false;
                }
            }
        }

        return true;
    }

    private function setOptions(array $options): void
    {
        /**
         * @var OptionInterface $option
         */
        foreach ($options as $key => $option) {
            //  Negative answer
            if ($option instanceof Option && $option->getPackages() === []) {
                $this->options[0] = $option;
            } else {
                $this->options[(int)$key + 1] = $option;
            }
        }

        if ($this->required !== true && !isset($this->options[0])) {
            $this->options[self::NONE_OPTION] = new Option(
                name: \count($this->options) === 1 ? 'No' : 'None of the above'
            );
        }
    }

    /**
     * @param array{require?: Packages[], require-dev?: Packages[]} $conditions
     */
    private function setConditions(array $conditions): void
    {
        foreach ($conditions['require'] ?? [] as $package) {
            $this->conditions['require'][] = new Package($package);
        }

        foreach ($conditions['require-dev'] ?? [] as $package) {
            $this->conditions['require-dev'][] = new Package($package);
        }
    }
}
