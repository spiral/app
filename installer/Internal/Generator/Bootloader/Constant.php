<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Bootloader;

use Nette\PhpGenerator\Literal;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\Exception\ReactorException;
use Spiral\Reactor\Partial\PhpNamespace;

final class Constant
{
    /** @var Value[] */
    private array $values = [];

    public function __construct(
        public readonly string $name,
        private readonly bool $protected = true,
    ) {
    }

    public function addValue(Value $binding): void
    {
        $this->values[] = $binding;
    }

    public function inject(ClassDeclaration $class, PhpNamespace $namespace): void
    {
        try {
            $constant = $class->getConstant($this->name);
        } catch (ReactorException) {
            $constant = $class->addConstant($this->name, []);
        }

        if ($this->protected) {
            $constant->setProtected();
        }

        $bindings = \array_map(
            static fn(Value $binding): string => $binding->render($namespace),
            $this->values,
        );

        $constant
            ->setValue([new Literal(\implode(',' . PHP_EOL, $bindings))]);
    }
}
