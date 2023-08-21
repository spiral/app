<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Module\RoadRunnerBridge\Common\RoadRunnerCliPackage;

final class RoadRunnerCli extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new RoadRunnerCliPackage());
    }
}
