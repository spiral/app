<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\LaravelSerializableClosure\Bootloaders;
use Installer\Package\Generator\LaravelSerializableClosure\Env;

final class LaravelSerializableClosure extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [],
        array $generators = [
            new Bootloaders(),
            new Env(),
        ]
    ) {
        parent::__construct(Packages::LaravelSerializableClosure, $resources, $generators);
    }
}
