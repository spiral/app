<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Traversable;

final class ResourceQueue implements \IteratorAggregate
{
    private const SOURCE = 0;
    private const DESTINATION = 1;

    public function __construct(
        private string $sourceRoot,
        private readonly \splQueue $queue = new \splQueue()
    ) {
    }

    public function setSourceRoot(string $sourceRoot): self
    {
        $this->sourceRoot = $sourceRoot;

        return $this;
    }

    public function copy(string $source, string $destination): self
    {
        $this->queue->push([
            self::SOURCE => \rtrim($this->sourceRoot, '/') . '/' . \ltrim($source, '/'),
            self::DESTINATION => $destination,
        ]);

        return $this;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->queue as $item) {
            yield $item[self::SOURCE] => $item[self::DESTINATION];
        }
    }
}
