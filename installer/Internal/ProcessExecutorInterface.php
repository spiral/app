<?php

declare(strict_types=1);

namespace Installer\Internal;

use Installer\Internal\Process\Output;

interface ProcessExecutorInterface
{
    /**
     * @param non-empty-string $command
     */
    public function execute(string $command): Output;
}
