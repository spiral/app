<?php

declare(strict_types=1);

namespace Installer\Internal\Process;

use Installer\Internal\Console\Output as ConsoleOutput;
use Symfony\Component\Process\Process;
use Traversable;

final class Output implements \IteratorAggregate
{
    public function __construct(
        public readonly string $command,
        private readonly \Generator $output,
    ) {
    }

    public function getIterator(): Traversable
    {
        foreach ($this->output as $type => $data) {
            yield match ($type) {
                Process::OUT => ConsoleOutput::write($data),
                Process::ERR => ConsoleOutput::error($data),
            };
        }
    }
}
