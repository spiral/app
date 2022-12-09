<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\Scheduler\Bootloaders;

final class Scheduler extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [],
        array $generators = [
            new Bootloaders(),
        ],
        array $instructions = [
            'Documentation: <comment>https://spiral.dev/docs/packages-scheduler</comment>'
        ]
    ) {
        parent::__construct(Packages::Scheduler, $resources, $generators, $instructions);
    }
}
