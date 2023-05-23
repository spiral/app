<?php

declare(strict_types=1);

namespace Installer\Internal;

use Installer\Internal\Console\Output;

interface ProcessExecutorInterface
{
    /**
     * @param non-empty-string $command
     * @return \Generator<Output>
     */
    public function execute(string $command): \Generator;
}
