<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge\Collections;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package;

final class IlluminateCollections extends Package
{
    public function __construct()
    {
        parent::__construct(ComposerPackages::IlluminateCollections);
    }
}
