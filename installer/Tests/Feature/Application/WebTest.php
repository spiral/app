<?php

declare(strict_types=1);

namespace Tests\Feature\Application;

use Installer\Application\Web\Application;
use Tests\Feature\InstallerTestCase;
use Installer\Module;

final class WebTest extends InstallerTestCase
{
    public function testInstallDefault(): void
    {
        $output = $this
            ->install(Application::class)
            ->addAnswer(Module\SapiBridge\Question::class, true)
            ->addAnswer(Module\Cache\Question::class, true)
            ->addAnswer(Module\Queue\Question::class, true)
            ->run();

        file_put_contents('installer.log', $output);
    }
}
