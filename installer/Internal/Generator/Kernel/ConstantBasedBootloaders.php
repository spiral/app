<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Kernel;

use Nette\PhpGenerator\Literal;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\Exception\ReactorException;
use Spiral\Reactor\Partial\PhpNamespace;

final class ConstantBasedBootloaders extends Bootloaders
{
    public function updateDeclaration(ClassDeclaration $class, PhpNamespace $namespace): void
    {
        \uasort(
            $this->groups,
            static fn(ClassListGroup $a, ClassListGroup $b) => $a->priority <=> $b->priority,
        );

        $groups = \array_map(
            static fn(ClassListGroup $group) => $group->render($namespace),
            \array_values($this->groups),
        );

        $string = \trim(\implode(',' . PHP_EOL, $groups));

        if ($string === '') {
            return;
        }

        try {
            $constant = $class->getConstant($this->place->value);
        } catch (ReactorException) {
            $constant = $class->addConstant($this->place->value, []);
        }

        $constant->setValue([new Literal($string)]);
    }
}
