<?php

declare(strict_types=1);

namespace Tests\Feature;

use Spiral\Files\Files;
use Tests\Installer;
use Tests\TestCase;

abstract class InstallerTestCase extends TestCase
{
    protected ?string $testApplication = null;

    public function install(string $applicationClass): Installer
    {
        $app = Installer::create($this->getConfig(), $applicationClass, $this->getAppPath());

        $this->testApplication = (string) $app;

        return $app;
    }

    protected function tearDown(): void
    {
        if ($this->testApplication !== null) {
            if ((bool) \getenv('DELETE_TEST_APPLICATION')) {
                $files = new Files();
                $files->deleteDirectory($this->getAppPath() . '/' . $this->testApplication);
            }

            if ((bool) \getenv('DELETE_APPLICATION_LOGS')) {
                $files->delete($this->getAppPath() . '/logs/install-' . $this->testApplication . '.log');
            }

            $this->testApplication = null;
        }

        parent::tearDown();
    }
}
