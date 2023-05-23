<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Symfony\Component\Process\Process;

final class BashCommandExecutor
{
    public function execute(array $commands): \Generator
    {
        foreach ($commands as $command) {
            yield from (new Process(\explode(' ', $command)))->getIterator();
        }
    }
}
