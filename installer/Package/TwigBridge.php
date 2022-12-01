<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\TwigBridge\Bootloaders;

final class TwigBridge extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::TwigBridge,
            resources: [
                'packages/twig/views' => 'app/views'
            ],
            generators: [
                new Bootloaders()
            ]
        );
    }
}
