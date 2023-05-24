<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Env;

use Spiral\Files\FilesInterface;
use Traversable;

final class Generator implements \Stringable, \IteratorAggregate
{
    private const FILENAME = '.env.sample';

    /** @var EnvGroup[] */
    private array $groups = [];

    public function __construct(
        private readonly string $projectRoot,
        private readonly FilesInterface $files,
    ) {
    }

    /**
     * @param array<non-empty-string, mixed> $values
     * @param ?non-empty-string $comment
     */
    public function addGroup(array $values, ?string $comment = null, int $priority = 0): self
    {
        // Check if the key exists in other groups
        // If it exists, then we will update an existing value instead of creating a new one
        // It prevents duplication of the same key
        foreach ($values as $key => $value) {
            if ($this->keyExists($key)) {
                $this->addValue($key, $value);
                unset($values[$key]);
            }
        }

        $this->groups[] = new EnvGroup($values, $comment, $priority);

        return $this;
    }

    /**
     * @param non-empty-string $key
     */
    public function addValue(string $key, mixed $value): self
    {
        $founded = false;
        foreach ($this->groups as $group) {
            if ($group->hasValue($key)) {
                $group->addValue($key, $value);
                $founded = true;
                break;
            }
        }

        if (!$founded) {
            $this->addGroup(values: [$key => $value], priority: 100);
        }

        return $this;
    }

    public function persist(): void
    {
        $this->files->write(
            $this->projectRoot . self::FILENAME,
            (string)$this,
            FilesInterface::RUNTIME
        );

        /**
         * Copy the .env.sample file to the project root.
         */
        $this->files->copy(
            $this->projectRoot . self::FILENAME,
            $this->projectRoot . '.env',
        );
    }

    public function __toString(): string
    {
        \uasort($this->groups, static fn(EnvGroup $a, EnvGroup $b) => $a->priority <=> $b->priority);

        $groups = \array_map(
            static fn(EnvGroup $group): string => (string)$group,
            $this->groups
        );

        return \trim(\implode(PHP_EOL, $groups)) . PHP_EOL;
    }

    private function keyExists(string $key): bool
    {
        foreach ($this->groups as $group) {
            if ($group->hasValue($key)) {
                return true;
            }
        }

        return false;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->groups as $group) {
            yield from $group;
        }
    }
}
