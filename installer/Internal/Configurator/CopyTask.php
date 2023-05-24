<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

final class CopyTask implements \Stringable
{
    public function __construct(
        public readonly string $source,
        public readonly string $destination,
        public readonly string $sourceRoot = '',
        public readonly string $destinationRoot = '',
    ) {
    }

    public function withSourceRoot(string $sourceRoot): self
    {
        return new self(
            source: $this->source,
            destination: $this->destination,
            sourceRoot: $sourceRoot,
            destinationRoot: $this->destinationRoot,
        );
    }

    public function withDestinationRoot(string $destinationRoot): self
    {
        return new self(
            source: $this->source,
            destination: $this->destination,
            sourceRoot: $this->sourceRoot,
            destinationRoot: $destinationRoot,
        );
    }

    public function getFullSource(): string
    {
        $sourceRoot = \rtrim($this->sourceRoot, '/');
        $source = \ltrim($this->source, '/');

        if ($sourceRoot === '') {
            return $source;
        }

        return $sourceRoot . '/' . $source;
    }

    public function getFullDestination(): string
    {
        $destinationRoot = \rtrim($this->destinationRoot, '/');
        $destination = \ltrim($this->destination, '/');

        if ($destinationRoot === '') {
            return $destination;
        }

        return $destinationRoot . '/' . $destination;
    }

    public function __toString(): string
    {
        return \sprintf(
            'Copy [%s:%s] -> [%s]',
            $this->getFullSource(),
            (\is_dir($this->getFullSource()) ? 'dir' : \file_exists($this->getFullSource())) ? 'file' : 'missing',
            $this->getFullDestination(),
        );
    }
}
