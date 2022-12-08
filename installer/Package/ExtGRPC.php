<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;

final class ExtGRPC extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(array $resources = [], array $generators = [])
    {
        parent::__construct(Packages::ExtGRPC, $resources, $generators);
    }
}
