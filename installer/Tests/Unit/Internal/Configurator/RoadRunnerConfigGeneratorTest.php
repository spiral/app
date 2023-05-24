<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Configurator;

use Installer\Internal\Configurator\RoadRunnerConfigGenerator;
use Installer\Internal\Console\Output;
use Installer\Internal\ProcessExecutorInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class RoadRunnerConfigGeneratorTest extends TestCase
{
    public static function providePlugins(): \Traversable
    {
        yield 'without plugins' => [
            [],
            'rr make-config',
        ];

        yield 'with plugins' => [
            ['plugin1', 'plugin2'],
            'rr make-config -p plugin1 -p plugin2',
        ];
    }

    #[DataProvider('providePlugins')]
    public function testGenerate(array $plugins, string $expected)
    {
        $executor = \Mockery::mock(ProcessExecutorInterface::class);

        $executor->shouldReceive('execute')
            ->with($expected)
            ->andReturn(
                (function () {
                    yield Output::success('test');
                })()
            );

        $generator = new RoadRunnerConfigGenerator($executor);

        $this->assertSame(
            '[success] test',
            (string)$generator->generate($plugins)->current()
        );
    }
}
