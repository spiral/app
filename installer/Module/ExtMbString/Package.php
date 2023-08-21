<?php

declare(strict_types=1);

namespace Installer\Module\ExtMbString;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(package: ComposerPackages::ExtMbString);
    }
}
