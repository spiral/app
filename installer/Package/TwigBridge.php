<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\TwigBridge\Bootloaders;

final class TwigBridge extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [
            'packages/twig/views' => 'app/views',
        ],
        array $generators = [
            new Bootloaders(),
        ]
    ) {
        parent::__construct(Packages::TwigBridge, $resources, $generators);
    }
}
