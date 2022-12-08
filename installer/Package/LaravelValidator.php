<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\LaravelValidator\Bootloaders;

final class LaravelValidator extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [],
        array $generators = [
            new Bootloaders(),
        ]
    ) {
        parent::__construct(Packages::LaravelValidator, $resources, $generators);
    }
}
