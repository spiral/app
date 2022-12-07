<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\LaravelValidator\Bootloaders;

final class LaravelValidator extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::LaravelValidator,
            generators: [
                new Bootloaders(),
            ]
        );
    }
}