<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Installer\Internal\Console\Output;
use Installer\Internal\ProcessExecutorInterface;

final class RoadRunnerConfigGenerator
{
    public function __construct(
        private readonly ProcessExecutorInterface $executor,
    ) {
    }

    /**
     * @param string[] $plugins
     * @return \Generator<Output>
     */
    public function generate(array $plugins): \Generator
    {
        $options = '';
        if (\count($plugins) > 0) {
            $options = ' -p ' . \implode(' -p ', $plugins);
        }

        yield from $this->executor->execute('rr make-config' . $options);
    }
}
