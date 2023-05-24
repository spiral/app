<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\Installer;
use Tests\TestCase;

abstract class InstallerTestCase extends TestCase
{
    public function install(string $applicationClass): Installer
    {
        return Installer::create($applicationClass);
    }
}
