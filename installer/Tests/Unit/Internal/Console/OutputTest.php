<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Console;

use Installer\Internal\Console\IOInterface;
use Installer\Internal\Console\Output;
use Tests\TestCase;

final class OutputTest extends TestCase
{
    public function testToString(): void
    {
        $output = new Output('Some message', 'type');
        $this->assertEquals('[type] Some message', (string) $output);
    }

    public function testSend(): void
    {
        $io = \Mockery::mock(IOInterface::class);

        $io->shouldReceive('type')
            ->once()
            ->with('Some message');

        $output = new Output('Some message', 'type');

        $output->send($io);
    }

    public function testWrite(): void
    {
        $output = Output::write('Some message');

        $this->assertEquals('[write] Some message', (string) $output);
    }

    public function testError(): void
    {
        $output = Output::error('Some message');

        $this->assertEquals('[error] Some message', (string) $output);
    }

    public function testSuccess(): void
    {
        $output = Output::success('Some message');

        $this->assertEquals('[success] Some message', (string) $output);
    }

    public function testComment(): void
    {
        $output = Output::comment('Some message');

        $this->assertEquals('[comment] Some message', (string) $output);
    }
}
