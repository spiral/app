<?php

declare(strict_types=1);

namespace Installer\Internal;

use Installer\Internal\Console\Output;
use Symfony\Component\Process\Process;

final class ProcessExecutor implements ProcessExecutorInterface
{
    public function execute(string $command): \Generator
    {
        foreach ((new Process(\explode(' ', $command)))->getIterator() as $type => $data) {
            yield match ($type) {
                Process::OUT => Output::write($data),
                Process::ERR => Output::error($data),
            };
        }
    }
}
