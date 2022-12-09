<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\SymfonySerializer\Bootloaders;
use Installer\Package\Generator\SymfonySerializer\Env;

final class SymfonySerializer extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [],
        array $generators = [
            new Bootloaders(),
            new Env()
        ]
    ) {
        parent::__construct(Packages::SymfonySerializer, $resources, $generators);
    }
}
