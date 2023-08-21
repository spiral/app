<?php

declare(strict_types=1);

namespace Installer\Internal\Process;

use Installer\Internal\ProcessExecutorInterface;
use Symfony\Component\Process\Process;

final class ProcessExecutor implements ProcessExecutorInterface
{
    public function execute(string $command): Output
    {
        $process = new Process(\explode(' ', $command));
        $process->start();

        return new Output(
            \str_replace('\'', '', $process->getCommandLine()),
            $process->getIterator()
        );
    }
}
