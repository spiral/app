<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Generator\Bootloader;

use Installer\Internal\Generator\Bootloader\ClassMethodBinding;
use Spiral\Reactor\Partial\PhpNamespace;
use Tests\TestCase;

final class ClassMethodBindingTest extends TestCase
{
    public function testRender(): void
    {
        $binding = new ClassMethodBinding(
            alias: 'App\Foo',
            methodName: 'domainCore',
        );

        $this->assertSame(
            "Foo::class => [self::class, 'domainCore']",
            $binding->render($namespace = new PhpNamespace('App')),
        );
    }
}
