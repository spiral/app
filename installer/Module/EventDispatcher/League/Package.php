<?php

declare(strict_types=1);

namespace Installer\Module\EventDispatcher\League;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Module\EventDispatcher\League\Generator\Bootloaders;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::LeagueEvent,
            generators: [
                new Bootloaders(),
            ],
            instructions: [
                'Documentation: <comment>https://spiral.dev/docs/advanced-events</comment>',
            ]
        );
    }
}
