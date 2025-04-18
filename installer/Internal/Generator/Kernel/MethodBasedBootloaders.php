<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Kernel;

use Installer\Internal\Events\BootloadersInjected;
use Nette\PhpGenerator\Literal;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\Exception\ReactorException;
use Spiral\Reactor\Partial\PhpNamespace;

final class MethodBasedBootloaders extends Bootloaders
{
    private const METHODS_MAP = [
        'APP' => 'defineAppBootloaders',
        'LOAD' => 'defineBootloaders',
        'SYSTEM' => 'defineSystemBootloaders',
    ];

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

        $methodName = self::METHODS_MAP[$this->place->value];

        $string = \trim(\implode(',' . PHP_EOL, $groups));

        if ($string === '') {
            return;
        }

        try {
            $method = $class->getMethod($methodName);
        } catch (ReactorException) {
            $method = $class->addMethod($methodName);
        }

        $method->setReturnType('array');

        $string = \implode(
            PHP_EOL,
            \array_map(
                static fn(string $line) => '    ' . $line,
                \explode(PHP_EOL, $string),
            ),
        );

        $string = new Literal($string);

        $method->setBody(
            <<<PHP
            return [
            $string,
            ];
            PHP,
        );

        $this->eventStorage?->addEvent(
            new BootloadersInjected($class->getName(), $this->place, $this->groups),
        );
    }
}
