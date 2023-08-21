<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Generator\Bootloader;

use Installer\Internal\Generator\Bootloader\ClassBinding;
use Installer\Internal\Generator\Bootloader\Constant;
use Spiral\Reactor\FileDeclaration;
use Spiral\Reactor\Printer;
use Tests\Fixtures\ExceptionHandlerBootloader;
use Tests\TestCase;

final class ConstantTest extends TestCase
{
    public function testInject(): void
    {
        $constant = new Constant('BINDINGS', protected: true);

        $constant->addValue(new ClassBinding('App\Foo', 'App\Bar'));
        $constant->addValue(new ClassBinding('Baz\Foo', 'Baz\Bar'));


        $reflection = new \ReflectionClass(ExceptionHandlerBootloader::class);
        $declaration = FileDeclaration::fromCode(\file_get_contents($reflection->getFileName()));

        $constant->inject(
            $declaration->getClass(ExceptionHandlerBootloader::class),
            $declaration->getNamespaces()->get($reflection->getNamespaceName())
        );

        $this->assertSame(
            <<<PHP
            <?php

            declare(strict_types=1);

            namespace Tests\Fixtures;

            use App\Bar;
            use App\Foo;
            use Baz\Bar as Bar1;
            use Baz\Foo as Foo1;

            final class ExceptionHandlerBootloader
            {
                protected const BINDINGS = [
                    Foo::class => Bar::class,
                    Foo1::class => Bar1::class,
                ];
            }

            PHP,
            (new Printer())->print($declaration)
        );
    }
}
