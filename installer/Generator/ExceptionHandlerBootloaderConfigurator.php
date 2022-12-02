<?php

declare(strict_types=1);

namespace Installer\Generator;

use Nette\PhpGenerator\Literal;
use Spiral\Http\Middleware\ErrorHandlerMiddleware\EnvSuppressErrors;
use Spiral\Http\Middleware\ErrorHandlerMiddleware\SuppressErrorsInterface;

final class ExceptionHandlerBootloaderConfigurator extends AbstractConfigurator
{
    private const BINDINGS_CONSTANT = 'BINDINGS';

    private array $bindings = [
        SuppressErrorsInterface::class => EnvSuppressErrors::class
    ];

    public function addBinding(string $alias, string $resolver): void
    {
        $this->bindings[$alias] = $resolver;
    }

    public function __destruct()
    {
        $this->addBindings();
        $this->write();
    }

    private function addBindings(): void
    {
        $this->addUse(SuppressErrorsInterface::class);
        $this->addUse(EnvSuppressErrors::class);

        $bindings = [];
        foreach ($this->bindings as $alias => $resolver) {
            if (\class_exists($alias) || interface_exists($alias)) {
                $alias = $this->namespace->simplifyName($alias);
            }

            $bindings[] = \class_exists($resolver)
                ? $alias . '::class => ' . $this->namespace->simplifyName($resolver) . '::class'
                : $alias . '::class => ' . $resolver;
        }

        $this->declaration
            ->getClass($this->reflection->getName())
            ->addConstant(self::BINDINGS_CONSTANT, [new Literal(\implode(',' . PHP_EOL, $bindings))])
            ->setProtected();
    }
}
