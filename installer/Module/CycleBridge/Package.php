<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Package as BasePackage;
use Installer\Internal\Readme\Block\ListBlock;
use Installer\Internal\Readme\Section;
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
            'database' => 'app/database',
        ],
        array $generators = [
            new Bootloaders(),
            new Interceptors(),
            new Config(),
            new Env(),
            new Skeleton(),
        ],
    ) {
        parent::__construct(ComposerPackages::CycleBridge, $resources, $generators);
    }

    public function getReadme(): array
    {
        return [
            Section::Configuration->value => [
                new ListBlock([
                    'Database configuration file: `app/config/database.php`',
                    'Migrations configuration file: `app/config/migration.php`',
                    'Cycle ORM configuration file: `app/config/cycle.php`',
                    'Documentation: `https://spiral.dev/docs/basics-orm`',
                ], $this->getTitle()),
            ],
        ];
    }
}
