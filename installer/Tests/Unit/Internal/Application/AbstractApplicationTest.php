<?php

declare(strict_types=1);

namespace Internal\Application;

use Installer\Application\ApplicationSkeleton;
use Installer\Application\ComposerPackages;
use Installer\Internal\Package;
use Installer\Module\Cache\Question;
use Installer\Module\Storage\Generator\Config;
use Tests\Fixtures\Application;
use Tests\TestCase;

final class AbstractApplicationTest extends TestCase
{
    private Application $app;

    /** Question[] */
    private array $questions;

    /** Package[] */
    private array $packages;

    /** string[] */
    private array $instructions;

    private array $autoload;
    private array $autoloadDev;
    private array $resources;
    private array $commands;
    private array $generators;

    public function testGetName(): void
    {
        $this->assertSame('Foo', $this->app->getName());
    }

    public function testGetPackages(): void
    {
        $this->assertSame(
            $this->packages,
            $this->app->getPackages(),
        );
    }

    public function testGetQuestions(): void
    {
        $this->assertSame(
            $this->questions,
            $this->app->getQuestions(),
        );
    }

    public function testGetInstructions(): void
    {
        $this->assertSame(
            $this->instructions,
            $this->app->getReadme(),
        );
    }

    public function testGetResources(): void
    {
        $this->assertSame(
            $this->resources,
            $this->app->getResources(),
        );
    }

    public function testGetAutoload(): void
    {
        $this->assertSame(
            $this->autoload,
            $this->app->getAutoload(),
        );
    }

    public function testGetAutoloadDev(): void
    {
        $this->assertSame(
            $this->autoloadDev,
            $this->app->getAutoloadDev(),
        );
    }

    public function testGetGenerators(): void
    {
        $generators = [];

        foreach ($this->app->getGenerators() as $generator) {
            $generators[] = $generator;
        }

        $this->assertSame(
            $this->generators,
            $generators,
        );
    }

    public function testGetCommands(): void
    {
        $this->assertSame(
            $this->commands,
            $this->app->getCommands(),
        );
    }

    public function testGetGeneratedResourcePath(): void
    {
        $this->assertTrue(\str_ends_with($this->app->getResourcesPath(), 'installer/Tests/Fixtures'));
    }

    public function testRoadRunnerPlugins(): void
    {
        $this->assertFalse($this->app->isRoadRunnerPluginRequired('kv'));
        $this->app->useRoadRunnerPlugin('kv');
        $this->assertTrue($this->app->isRoadRunnerPluginRequired('kv'));
        $this->assertFalse($this->app->isRoadRunnerPluginRequired('jobs'));
        $this->app->useRoadRunnerPlugin('jobs');
        $this->assertTrue($this->app->isRoadRunnerPluginRequired('jobs'));

        $this->assertSame(['kv', 'jobs'], $this->app->getRoadRunnerPlugins());
    }

    public function testInstalledPackages(): void
    {
        $package = new Package(ComposerPackages::ExtGRPC);

        $this->assertFalse($this->app->isPackageInstalled($package));

        $this->app->setInstalled([$package->getName()], []);

        $this->assertTrue($this->app->isPackageInstalled($package));
    }

    public function testAnsweredQuestions(): void
    {
        $question = 'Foo';

        $this->assertNull($this->app->getOption($question));

        $this->app->setInstalled([], [$question => 'Bar']);

        $this->assertSame('Bar', $this->app->getOption($question));
    }

    public function testHasSkeleton(): void
    {
        $this->assertFalse($this->app->hasSkeleton());

        $this->app->setInstalled([], [ApplicationSkeleton::class => false]);
        $this->assertFalse($this->app->hasSkeleton());

        $this->app->setInstalled([], [ApplicationSkeleton::class => true]);
        $this->assertTrue($this->app->hasSkeleton());
    }

    public function testGetDefaultInstructions(): void
    {
        $app = new Application(name: 'Foo');

        $this->assertSame(
            [
                'Some default instruction',
            ],
            $app->getReadme(),
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = new Application(
            name: 'Foo',
            packages: $this->packages = [
                new Package(ComposerPackages::ExtGRPC),
            ],
            autoload: $this->autoload = [
                'psr-4' => [
                    'Foo\\' => 'src/',
                ],
            ],
            autoloadDev: $this->autoloadDev = [
                'psr-4' => [
                    'Baz\\' => 'src/',
                ],
            ],
            questions: $this->questions = [
                new Question(),
            ],
            resources: $this->resources = [
                '/foo/bar' => 'baz',
            ],
            generators: $this->generators = [
                new Config(),
            ],
            commands: $this->commands = [
                'foo' => 'bar',
            ],
            readme: $this->instructions = [
                'foo' => 'bar',
            ],
        );
    }
}
