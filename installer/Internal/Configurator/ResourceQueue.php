<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Installer\Internal\Events\CopyEvent;

final class ResourceQueue implements \IteratorAggregate, \Countable
{
    /**
     * @param \splQueue<CopyEvent> $queue
     */
    public function __construct(
        private string $sourceRoot = '',
        private readonly \splQueue $queue = new \splQueue(),
        private readonly array $directoriesMap = [],
    ) {
        $queue->setIteratorMode(\SplQueue::IT_MODE_DELETE);
    }

    public function setSourceRoot(string $sourceRoot): self
    {
        $this->sourceRoot = $sourceRoot;

        return $this;
    }

    public function copy(string $source, string $destination): self
    {
        foreach ($this->directoriesMap as $alias => $path) {
            if (\str_starts_with($source, $alias)) {
                $source = \str_replace($alias, $path, $source);
            }
        }

        $this->queue->push(
            new CopyEvent(
                source: \ltrim($source, '/'),
                destination: \ltrim($destination, '/'),
                sourceRoot: \rtrim($this->sourceRoot, '/'),
            ),
        );

        return $this;
    }

    /**
     * @return \Traversable<array-key, CopyEvent>
     */
    public function getIterator(): \Traversable
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
