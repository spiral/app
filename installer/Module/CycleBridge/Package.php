<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Package as BasePackage;
use Installer\Module\CycleBridge\Generator\Bootloaders;
use Installer\Module\CycleBridge\Generator\Config;
use Installer\Module\CycleBridge\Generator\Env;
use Installer\Module\CycleBridge\Generator\Interceptors;
use Installer\Module\CycleBridge\Generator\Skeleton;

final class Package extends BasePackage
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [
            'config' => 'app/config',
            'migrations' => 'app/migrations',
        ],
        array $generators = [
            new Bootloaders(),
            new Interceptors(),
            new Config(),
            new Env(),
            new Skeleton(),
        ],
        array $instructions = [
            'Database configuration file: <comment>app/config/database.php</comment>',
            'Migrations configuration file: <comment>app/config/migration.php</comment>',
            'Cycle ORM configuration file: <comment>app/config/cycle.php</comment>',
            'Documentation: <comment>https://spiral.dev/docs/basics-orm</comment>',
        ],
    ) {
        parent::__construct(ComposerPackages::CycleBridge, $resources, $generators, $instructions);
    }
}
