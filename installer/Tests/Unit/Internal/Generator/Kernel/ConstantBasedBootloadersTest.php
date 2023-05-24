<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Generator\Kernel;

use Installer\Internal\Generator\Kernel\BootloaderPlaces;
use Installer\Internal\Generator\Kernel\Bootloaders;
use Installer\Internal\Generator\Kernel\ConstantBasedBootloaders;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\Partial\PhpNamespace;
use Tests\TestCase;

final class ConstantBasedBootloadersTest extends TestCase
{
    private Bootloaders $bootloaders;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bootloaders = new ConstantBasedBootloaders(
            BootloaderPlaces::Load
        );
    }

    public function testAddGroup(): void
    {
        $this->bootloaders->addGroup([
            'App\Bootloader\FirstBootloader',
            'App\Bootloader\SecondBootloader',
        ], 'Test Bootloaders group', 0);

        $this->assertSame(
            <<<'PHP'
            class Kernel
            {
                public const LOAD = [
                    // Test Bootloaders group
                    Bootloader\FirstBootloader::class,
                    Bootloader\SecondBootloader::class,
                ];
            }

            PHP
            ,
            $this->renderBootloaders($this->bootloaders)
        );
    }

    public function testPrepend(): void
    {
        $this->bootloaders->append('App\Bootloader\FirstBootloader');

        $this->assertSame(
            <<<'PHP'
            class Kernel
            {
                public const LOAD = [Bootloader\FirstBootloader::class];
            }

            PHP
            ,
            $this->renderBootloaders($this->bootloaders)
        );

        $this->bootloaders->prepend('App\Bootloader\SecondBootloader');

        $this->assertSame(
            <<<'PHP'
            class Kernel
            {
                public const LOAD = [
                    Bootloader\SecondBootloader::class,

                    Bootloader\FirstBootloader::class,
                ];
            }

            PHP
            ,
            $this->renderBootloaders($this->bootloaders)
        );

        $this->bootloaders->prepend('App\Bootloader\ThirdBootloader', 'App\Bootloader\FirstBootloader');

        $this->assertSame(
            <<<'PHP'
            class Kernel
            {
                public const LOAD = [
                    Bootloader\SecondBootloader::class,

                    Bootloader\ThirdBootloader::class,
                    Bootloader\FirstBootloader::class,
                ];
            }

            PHP
            ,
            $this->renderBootloaders($this->bootloaders)
        );

        $this->bootloaders->addGroup([
            'App\Bootloader\FourthBootloader',
            'App\Bootloader\FifthBootloader',
        ], 'Test Bootloaders group', 0);

        $this->bootloaders->prepend('App\Bootloader\SixthBootloader', 'App\Bootloader\FifthBootloader');

        $this->assertSame(
            <<<'PHP'
            class Kernel
            {
                public const LOAD = [
                    // Test Bootloaders group
                    Bootloader\FourthBootloader::class,
                    Bootloader\SixthBootloader::class,
                    Bootloader\FifthBootloader::class,

                    Bootloader\SecondBootloader::class,

                    Bootloader\ThirdBootloader::class,
                    Bootloader\FirstBootloader::class,
                ];
            }

            PHP
            ,
            $this->renderBootloaders($this->bootloaders)
        );
    }

    public function testAppend(): void
    {
        $this->bootloaders->append('App\Bootloader\FirstBootloader');

        $this->assertSame(
            <<<'PHP'
            class Kernel
            {
                public const LOAD = [Bootloader\FirstBootloader::class];
            }

            PHP
            ,
            $this->renderBootloaders($this->bootloaders)
        );

        $this->bootloaders->append('App\Bootloader\SecondBootloader');

        $this->assertSame(
            <<<'PHP'
            class Kernel
            {
                public const LOAD = [
                    Bootloader\FirstBootloader::class,

                    Bootloader\SecondBootloader::class,
                ];
            }

            PHP
            ,
            $this->renderBootloaders($this->bootloaders)
        );

        $this->bootloaders->append('App\Bootloader\ThirdBootloader', 'App\Bootloader\FirstBootloader');

        $this->assertSame(
            <<<'PHP'
            class Kernel
            {
                public const LOAD = [
                    Bootloader\FirstBootloader::class,
                    Bootloader\ThirdBootloader::class,

                    Bootloader\SecondBootloader::class,
                ];
            }

            PHP
            ,
            $this->renderBootloaders($this->bootloaders)
        );

        $this->bootloaders->addGroup([
            'App\Bootloader\FourthBootloader',
            'App\Bootloader\FifthBootloader',
        ], 'Test Bootloaders group', 0);

        $this->bootloaders->append('App\Bootloader\SixthBootloader', 'App\Bootloader\FourthBootloader');

        $this->assertSame(
            <<<'PHP'
            class Kernel
            {
                public const LOAD = [
                    // Test Bootloaders group
                    Bootloader\FourthBootloader::class,
                    Bootloader\SixthBootloader::class,
                    Bootloader\FifthBootloader::class,

                    Bootloader\FirstBootloader::class,
                    Bootloader\ThirdBootloader::class,

                    Bootloader\SecondBootloader::class,
                ];
            }

            PHP
            ,
            $this->renderBootloaders($this->bootloaders)
        );
    }

    public function testAppBootloaders(): void
    {
        $bootloaders = new ConstantBasedBootloaders(
            BootloaderPlaces::App
        );

        $bootloaders->append('Foo');

        $this->assertSame(
            <<<'PHP'
            class Kernel
            {
                public const APP = [\Foo::class];
            }

            PHP
            ,
            $this->renderBootloaders($bootloaders)
        );
    }

    public function testSystemBootloaders(): void
    {
        $bootloaders = new ConstantBasedBootloaders(
            BootloaderPlaces::System
        );

        $bootloaders->append('Foo');

        $this->assertSame(
            <<<'PHP'
            class Kernel
            {
                public const SYSTEM = [\Foo::class];
            }

            PHP
            ,
            $this->renderBootloaders($bootloaders)
        );
    }

    private function renderBootloaders(Bootloaders $bootloaders): string
    {
        $class = new ClassDeclaration('Kernel');
        $namespace = new PhpNamespace('App');

        $bootloaders->updateDeclaration($class, $namespace);

        return \str_replace("\t", '    ', $class->render());
    }
}
