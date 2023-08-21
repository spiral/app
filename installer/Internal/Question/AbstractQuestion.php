<?php

declare(strict_types=1);

namespace Installer\Internal\Question;

use Installer\Application\ComposerPackages;
use Installer\Internal\HasResourcesInterface;
use Installer\Internal\Installer\ApplicationState;
use Installer\Internal\Question\Option\Option;
use Installer\Internal\Question\Option\OptionInterface;

abstract class AbstractQuestion implements QuestionInterface, HasResourcesInterface
{
    protected const YES_OPTION = 1;
    protected const NONE_OPTION = 0;

    /**
     * @var array<int, OptionInterface>
     */
    private array $options = [];

    /**
     * @param array<int, OptionInterface> $options
     * @param ComposerPackages[] $depends
     */
    public function __construct(
        private readonly string $question,
        private readonly bool $required,
        array $options,
        private readonly array $depends = [],
        private readonly int $default = self::NONE_OPTION
    ) {
        $this->setOptions($options);
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

    public function getResourcesPath(): string
    {
        $dir = \dirname((new \ReflectionClass($this))->getFileName());

        $path = \rtrim($dir, '/') . '/resources/';

        if (\is_dir($path)) {
            return $path;
        }

        return $dir;
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

    public function getDefault(): int
    {
        return $this->default;
    }

    public function canAsk(ApplicationState $state): bool
    {
        foreach ($this->depends as $package) {
            if (!$state->isPackageInstalled($package)) {
                return false;
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
}
