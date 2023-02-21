<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\YiiErrorHandler\Bootloaders;

final class YiiErrorHandler extends Package
{
    public function __construct(
        array $generators = [
            new Bootloaders()
        ],
    ) {
        parent::__construct(Packages::YiiErrorHandler, generators: $generators);
    }
}