<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Installer\Internal\Process\Output;
use Installer\Internal\ProcessExecutorInterface;

final class RoadRunnerConfigGenerator
{
    public function __construct(
        private readonly ProcessExecutorInterface $executor,
    ) {}

    /**
     * @param string[] $plugins
     */
    public function generate(array $plugins): Output
    {
        $options = '';
        if (\count($plugins) > 0) {
            $options = ' -p ' . \implode(' -p ', $plugins);
        }

        return $this->executor->execute('rr make-config' . $options);
    }
}
