<?php

declare(strict_types=1);

namespace Installer\Internal\Generator;

use Installer\Internal\Resource;
use Spiral\Files\FilesInterface;

final class EnvConfigurator
{
    private const FILENAME = '.env.sample';

    /**
     * @var EnvGroup[]
     *
     */
    private array $groups = [];

    public function __construct(
        private readonly string $projectRoot,
        private readonly Resource $resource,
        private readonly FilesInterface $files,
    ) {
    }

    public function __destruct()
    {
        \uasort($this->groups, static fn(EnvGroup $a, EnvGroup $b) => $a->priority <=> $b->priority);

        $groups = \array_map(
            static fn(EnvGroup $group): string => $group->render(),
            \array_values($this->groups)
        );

        $this->files->write(
            $this->projectRoot . self::FILENAME,
            \ltrim(\implode(PHP_EOL, $groups)),
            FilesInterface::RUNTIME
        );

        $this->resource->createEnv();
    }

    /**
     * @param array<non-empty-string, mixed> $values
     * @param ?non-empty-string $comment
     */
    public function addGroup(array $values, ?string $comment = null, int $priority = 0): self
    {
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
}
