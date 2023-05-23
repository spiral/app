<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\Common;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;

final class ExtSocketsPackage extends BasePackage
{
    public function __construct()
    {
        parent::__construct(package: ComposerPackages::ExtSockets);
    }
}
