<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Installer\Internal\Console\IO;
use Symfony\Component\Process\Process;

final class RoadRunnerConfigGenerator
{
    public function __construct(
        private readonly IO $io,
    ) {
    }

    public function generate(array $plugins): string
    {
        $options = '';

        if (\count($plugins) > 0) {
            $options = ' -p ' . \implode(' -p ', $plugins);
        }

        (new Process(\explode(' ', 'rr make-config' . $options)))
            ->run(function (string $type, mixed $data) {
                $this->io->write($data);
            });
    }
}
