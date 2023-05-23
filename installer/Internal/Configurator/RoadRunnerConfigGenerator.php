<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Symfony\Component\Process\Process;

final class RoadRunnerConfigGenerator
{
    public function generate(array $plugins): \Generator
    {
        $options = '';
        if (\count($plugins) > 0) {
            $options = ' -p ' . \implode(' -p ', $plugins);
        }

        yield from (new Process(\explode(' ', 'rr make-config' . $options)))->getIterator();
    }
}
