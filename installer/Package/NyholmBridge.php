<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\NyholmBridge\Bootloaders;

final class NyholmBridge extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::NyholmBridge,
            generators: [
                new Bootloaders(),
            ]
        );
    }
}
