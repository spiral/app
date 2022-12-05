<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\CycleBridge\Bootloaders;
use Installer\Package\Generator\CycleBridge\Config;

final class CycleBridge extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::CycleBridge,
            resources: [
                'packages/cycle/config' => 'app/config',
                'packages/cycle/migrations' => 'app/migrations',
            ],
            generators: [
                new Bootloaders(),
                new Config(),
            ]
        );
    }
}
