<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;

final class IlluminateCollections extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(array $resources = [], array $generators = [])
    {
        parent::__construct(Packages::IlluminateCollections, $resources, $generators);
    }
}
