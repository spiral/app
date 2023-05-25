<?php

declare(strict_types=1);

namespace Installer\Internal\Generator;

use Installer\Internal\ClassMetadataInterface;
use Spiral\Reactor\FileDeclaration;
use Spiral\Reactor\Partial\PhpNamespace;
use Spiral\Reactor\Writer;

abstract class AbstractConfigurator
{
    protected FileDeclaration $declaration;
    protected PhpNamespace $namespace;

    public function __construct(
        protected readonly ClassMetadataInterface $class,
        private readonly Writer $writer,
    ) {
        $this->declaration = FileDeclaration::fromCode(\file_get_contents($this->class->getPath()));
        $this->namespace = $this->declaration->getNamespaces()->get($this->class->getNamespace());
    }

    public function addUse(string $name, ?string $alias = null): self
    {
        $this->namespace->addUse($name, $alias);

        return $this;
    }

    protected function write(): void
    {
        $this->writer->write($this->class->getPath(), $this->declaration);
    }
}
