<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\TemporalBridge\Bootloaders;

final class TemporalBridge extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::TemporalBridge,
            generators: [
                new Bootloaders()
            ]
        );
    }
}
