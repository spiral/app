<?php

declare(strict_types=1);

namespace Internal;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package;
use Installer\Module\Storage\Generator\Config;
use Tests\TestCase;

final class PackageTest extends TestCase
{
    private Package $package;
    private array $resources;
    private array $generators;
    private array $instructions;
    private array $dependencies;

    protected function setUp(): void
    {
        parent::setUp();

        $this->package = new Package(
            package: ComposerPackages::Scheduler,
            resources: $this->resources = [
                '/foo/bar' => 'baz'
            ],
            generators: $this->generators = [
                new Config()
            ],
            readme: $this->instructions = [
                'foo' => 'bar'
            ],
            dependencies: $this->dependencies = [
                new \Installer\Module\Dumper\Package()
            ],
        );
    }

    public function testGetName(): void
    {
        $this->assertSame('spiral-packages/scheduler', $this->package->getName());
    }

    public function testGetTitle(): void
    {
        $this->assertSame('Scheduler', $this->package->getTitle());
    }

    public function testGetVersion(): void
    {
        $this->assertSame('^2.1', $this->package->getVersion());
    }

    public function testGetResources(): void
    {
        $this->assertSame($this->resources, $this->package->getResources());
    }

    public function testGetGenerators(): void
    {
        $this->assertSame($this->generators, $this->package->getGenerators());
    }

    public function testGetInstructions(): void
    {
        $this->assertSame($this->instructions, $this->package->getReadme());
    }

    public function testGetDependencies(): void
    {
        $this->assertSame($this->dependencies, $this->package->getDependencies());
    }

    public function testIsDev(): void
    {
        $devPackage = new Package(ComposerPackages::Dumper);
        $this->assertTrue($devPackage->isDev());

        $prodPackage = new Package(ComposerPackages::Scheduler);
        $this->assertFalse($prodPackage->isDev());
    }

    public function testGetResourcesPath(): void
    {
        $this->assertTrue(\str_ends_with($this->package->getResourcesPath(), 'Internal'));

        $cycle = new \Installer\Module\CycleBridge\Package();

        $this->assertTrue(\str_ends_with($cycle->getResourcesPath(), 'Module/CycleBridge/resources/'));
    }
}
