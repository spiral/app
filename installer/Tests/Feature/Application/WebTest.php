<?php

declare(strict_types=1);

namespace Tests\Feature\Application;

use Installer\Application\Web\Application;
use Installer\Module;
use Tests\Feature\InstallerTestCase;

final class WebTest extends InstallerTestCase
{
    public function testInstallDefault(): void
    {
        $result = $this
            ->install(Application::class)
            ->withSkeleton()
            ->addAnswer(Module\SapiBridge\Question::class, true)
            ->addAnswer(Module\Cache\Question::class, true)
            ->addAnswer(Module\Queue\Question::class, true)
            ->run();

        $result->storeLog();
    }
}
