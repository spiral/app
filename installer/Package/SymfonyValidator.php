<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\SymfonyValidator\Bootloaders;

final class SymfonyValidator extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::SymfonyValidator,
            generators: [
                new Bootloaders(),
            ]
        );
    }
}
