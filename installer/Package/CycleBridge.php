<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\CycleBridge\Bootloaders;
use Installer\Package\Generator\CycleBridge\Config;
use Installer\Package\Generator\CycleBridge\Env;

final class CycleBridge extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [
            'packages/cycle/config' => 'app/config',
            'packages/cycle/migrations' => 'app/migrations',
        ],
        array $generators = [
            new Bootloaders(),
            new Config(),
            new Env(),
        ],
        array $instructions = [
            'Database configuration file available here: <comment>app/config/database.php</comment>',
            'Migrations configuration file available here: <comment>app/config/migration.php</comment>',
            'Cycle ORM configuration file available here: <comment>app/config/cycle.php</comment>',
            'Documentation: <comment>https://spiral.dev/docs/packages-cycle-bridge</comment>'
        ]
    ) {
        parent::__construct(Packages::CycleBridge, $resources, $generators, $instructions);
    }
}
