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

    /**
     * `rr make-config` lists the placeholder `proto/helloworld.proto` in the `grpc` section;
     * RoadRunner refuses to start while a listed proto file is missing.
     */
    public function replaceProto(string $config, string $proto): string
    {
        return \str_replace('proto/helloworld.proto', $proto, $config);
    }
}
