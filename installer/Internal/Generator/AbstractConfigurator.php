<?php

declare(strict_types=1);

namespace Installer\Internal\Generator;

use Spiral\Reactor\FileDeclaration;
use Spiral\Reactor\Partial\PhpNamespace;
use Spiral\Reactor\Writer;

abstract class AbstractConfigurator
{
    protected FileDeclaration $declaration;
    protected \ReflectionClass $reflection;
    protected PhpNamespace $namespace;

    /**
     * @param class-string $class
     */
    public function __construct(
        string $class,
        private readonly Writer $writer,
    ) {
        $this->reflection = new \ReflectionClass($class);
        $this->declaration = FileDeclaration::fromCode(\file_get_contents($this->reflection->getFileName()));
        $this->namespace = $this->declaration->getNamespaces()->get($this->reflection->getNamespaceName());
    }

    public function addUse(string $name, ?string $alias = null): self
    {
        $this->namespace->addUse($name, $alias);

        return $this;
    }

    protected function write(): void
    {
        $this->writer->write($this->reflection->getFileName(), $this->declaration);
    }
}
