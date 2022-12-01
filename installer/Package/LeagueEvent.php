<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\LeagueEvent\Bootloaders;

final class LeagueEvent extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::LeagueEvent,
            generators: [
                new Bootloaders(),
            ]
        );
    }
}
