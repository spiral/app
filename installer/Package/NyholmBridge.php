<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\NyholmBridge\Bootloaders;

final class NyholmBridge extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [],
        array $generators = [
            new Bootloaders(),
        ],
        array $instructions = []
    ) {
        parent::__construct(Packages::NyholmBridge, $resources, $generators, $instructions);
    }
}
