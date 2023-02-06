<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;

final class LoophpCollections extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(array $resources = [], array $generators = [], array $instructions = [])
    {
        parent::__construct(Packages::LoophpCollections, $resources, $generators, $instructions);
    }
}
