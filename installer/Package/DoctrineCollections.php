<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;

final class DoctrineCollections extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(array $resources = [], array $generators = [], array $instructions = [])
    {
        parent::__construct(Packages::DoctrineCollections, $resources, $generators, $instructions);
    }
}
