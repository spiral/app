<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Generator\Bootloader;

use Installer\Internal\Generator\Bootloader\ClassBinding;
use Nette\PhpGenerator\Printer;
use Spiral\Reactor\Partial\PhpNamespace;
use Tests\TestCase;

final class ClassBindingTest extends TestCase
{
    public function testRender(): void
    {
        $binding = new ClassBinding(
            alias: 'App\Foo',
            value: 'Baz\Bar'
        );

        $this->assertSame(
            'Foo::class => Bar::class',
            $binding->render($namespace = new PhpNamespace('App'))
        );

        $printer = new Printer();
        $printer->omitEmptyNamespaces = false;

        $this->assertSame(
            <<<PHP
            namespace App;

            use Baz\Bar;


            PHP,
            $printer->printNamespace($namespace->getElement())
        );
    }
}
