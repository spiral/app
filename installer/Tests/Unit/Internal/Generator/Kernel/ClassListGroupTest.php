<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Generator\Kernel;

use Installer\Internal\Generator\Kernel\ClassListGroup;
use Spiral\Reactor\Partial\PhpNamespace;
use Tests\TestCase;

final class ClassListGroupTest extends TestCase
{
    protected ClassListGroup $group;
    protected PhpNamespace $namespace;

    public function setUp(): void
    {
        parent::setUp();

        $this->group = new ClassListGroup([
            'App\Bootloader\FirstBootloader',
            'App\Bootloader\SecondBootloader',
        ], 'Test Bootloaders group', 0);

        $this->namespace = new PhpNamespace('');
    }

    public function testHasBootloader(): void
    {
        $this->assertTrue(
            $this->group->hasClass('App\Bootloader\FirstBootloader')
        );

        $this->assertFalse(
            $this->group->hasClass('App\Bootloader\ThirdBootloader')
        );

        $this->group->append('App\Bootloader\ThirdBootloader');

        $this->assertTrue(
            $this->group->hasClass('App\Bootloader\ThirdBootloader')
        );
    }

    public function testAppendToTheEndOfList(): void
    {
        $this->group->append('App\Bootloader\ThirdBootloader');

        $this->assertSame(
            <<<PHP

            // Test Bootloaders group
            App\Bootloader\FirstBootloader::class,
            App\Bootloader\SecondBootloader::class,
            App\Bootloader\ThirdBootloader::class
            PHP
            ,
            $this->group->render($this->namespace)
        );
    }

    public function testAppendAfterSelected(): void
    {
        $this->group->append('App\Bootloader\ThirdBootloader', 'App\Bootloader\FirstBootloader');

        $this->assertSame(
            <<<PHP

            // Test Bootloaders group
            App\Bootloader\FirstBootloader::class,
            App\Bootloader\ThirdBootloader::class,
            App\Bootloader\SecondBootloader::class
            PHP
            ,
            $this->group->render($this->namespace)
        );
    }

    public function testPrependToTheTopOfList(): void
    {
        $this->group->prepend('App\Bootloader\ThirdBootloader');

        $this->assertSame(
            <<<PHP

            // Test Bootloaders group
            App\Bootloader\ThirdBootloader::class,
            App\Bootloader\FirstBootloader::class,
            App\Bootloader\SecondBootloader::class
            PHP
            ,
            $this->group->render($this->namespace)
        );
    }

    public function testPrependBeforeSelected(): void
    {
        $this->group->prepend('App\Bootloader\ThirdBootloader', 'App\Bootloader\SecondBootloader');

        $this->assertSame(
            <<<PHP

            // Test Bootloaders group
            App\Bootloader\FirstBootloader::class,
            App\Bootloader\ThirdBootloader::class,
            App\Bootloader\SecondBootloader::class
            PHP
            ,
            $this->group->render($this->namespace)
        );
    }
}
