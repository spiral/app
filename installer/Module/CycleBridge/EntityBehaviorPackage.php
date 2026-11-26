<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package;

final class EntityBehaviorPackage extends Package
{
    public function __construct()
    {
        parent::__construct(ComposerPackages::CycleEntityBehavior);
    }
}
