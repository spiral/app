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
        ]
    ) {
        parent::__construct(Packages::CycleBridge, $resources, $generators);
    }
}
