<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\Mailer\Bootloaders;
use Installer\Package\Generator\Mailer\Env;

final class Mailer extends Package
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
        parent::__construct(null, $resources, $generators);
    }
}
