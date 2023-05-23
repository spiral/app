<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge\GRPC;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package;

final class ExtGRPC extends Package
{
    public function __construct()
    {
        parent::__construct(ComposerPackages::ExtGRPC);
    }
}
