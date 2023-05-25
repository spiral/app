<?php

declare(strict_types=1);

namespace Tests\Feature;

use Spiral\Files\Files;
use Tests\Installer;
use Tests\TestCase;

abstract class InstallerTestCase extends TestCase
{
    public function install(string $applicationClass): Installer
    {
        return Installer::create($this->getConfig(), $applicationClass, $this->getAppPath());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
