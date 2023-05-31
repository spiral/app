<?php

declare(strict_types=1);

namespace Tests\Feature;

use Spiral\Files\Files;
use Tests\Installer;
use Tests\TestCase;

abstract class InstallerTestCase extends TestCase
{
    protected ?string $testApplicationPath = null;

    public function install(string $applicationClass): Installer
    {
        $app = Installer::create($this->getConfig(), $applicationClass, $this->getAppPath());

        $this->testApplicationPath = $this->getAppPath() . '/' . (string) $app;

        return $app;
    }

    protected function tearDown(): void
    {
        if ($this->testApplicationPath !== null) {
            $files = new Files();
            $files->deleteDirectory($this->testApplicationPath);

            $this->testApplicationPath = null;
        }

        parent::tearDown();
    }
}
