<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Installer\Internal\Console\IO;
use Symfony\Component\Process\Process;

final class BashCommandExecutor
{
    public function __construct(
        private readonly IO $io,
    ) {
    }

    public function execute(array $commands): void
    {
        foreach ($commands as $command) {
            (new Process(\explode(' ', $command)))
                ->run(function (string $type, mixed $data) {
                    $this->io->write($data);
                });
        }
    }
}
