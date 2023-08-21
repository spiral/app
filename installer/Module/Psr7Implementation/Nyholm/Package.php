<?php

declare(strict_types=1);

namespace Installer\Module\Psr7Implementation\Nyholm;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Module\Psr7Implementation\Nyholm\Generator\Bootloaders;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::NyholmBridge,
            generators: [
                new Bootloaders(),
            ],
        );
    }
}
