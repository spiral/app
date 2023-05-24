<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use splQueue;
use Traversable;

final class ResourceQueue implements \IteratorAggregate, \Countable
{
    /**
     * @param splQueue<CopyTask> $queue
     */
    public function __construct(
        private string $sourceRoot,
        private readonly splQueue $queue = new splQueue()
    ) {
        $queue->setIteratorMode(SplQueue::IT_MODE_DELETE);
    }

    public function setSourceRoot(string $sourceRoot): self
    {
        $this->sourceRoot = $sourceRoot;

        return $this;
    }

    public function copy(string $source, string $destination): self
    {
        $this->queue->push(
            new CopyTask(
                source: \ltrim($source, '/'),
                destination: \ltrim($destination, '/'),
                sourceRoot: \rtrim($this->sourceRoot, '/'),
            )
        );

        return $this;
    }

    /**
     * @return Traversable<array-key, CopyTask>
     */
    public function getIterator(): Traversable
    {
        foreach ($this->queue as $item) {
            yield $item;
        }
    }

    public function count(): int
    {
        return $this->queue->count();
    }
}
