<?php

declare(strict_types=1);

namespace Tests;

final class InstallationResult
{
    public function __construct(
        public readonly string $appPath,
        public readonly string $log
    ) {
    }

    public function storeLog(): void
    {
        \file_put_contents($this->appPath . '/install.log', $this->log);
    }
}
