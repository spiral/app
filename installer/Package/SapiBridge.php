<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\SapiBridge\Bootloaders;

final class SapiBridge extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::SapiBridge,
            generators: [
                new Bootloaders(),
            ]
        );
    }
}
