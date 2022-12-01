<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\SpiralValidator\Bootloaders;

final class SpiralValidator extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::SpiralValidator,
            generators: [
                new Bootloaders(),
            ]
        );
    }
}
