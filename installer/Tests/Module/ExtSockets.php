<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Module\RoadRunnerBridge\Common\ExtSocketsPackage;

final class ExtSockets extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new ExtSocketsPackage());
    }
}
