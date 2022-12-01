<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\StemplerBridge\Bootloaders;

final class StemplerBridge extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::StemplerBridge,
            resources: [
                'packages/stempler/views' => 'app/views'
            ],
            generators: [
                new Bootloaders()
            ]
        );
    }
}
