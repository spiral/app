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
        return $this->normalizePath($this->sourceRoot, $this->source);
    }

    public function getFullDestination(): string
    {
        return $this->normalizePath($this->destinationRoot, $this->destination);
    }

    public function __toString(): string
    {
        return \sprintf(
            'Copy [%s:%s] -> [%s]',
            $this->getFullSource(),
            $this->detectType($this->getFullSource()),
            $this->getFullDestination(),
        );
    }

    public function normalizePath(string $root, string $path): string
    {
        $sourceRoot = \trim($root, '/');
        $source = \ltrim($path, '/');

        if ($sourceRoot === '') {
            return '/' . $source;
        }

        return '/' . $sourceRoot . '/' . $source;
    }

    public function detectType(string $path): string
    {
        $type = 'missing';
        if (\is_dir($path)) {
            $type = 'dir';
        } elseif (\is_file($path)) {
            $type = 'file';
        }

        return $type;
    }
}
