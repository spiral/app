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
        ],
        array $instructions = [
            'Read more about views in the Spiral Framework: <comment>https://spiral.dev/docs/views-configuration</comment>',
            'Documentation: <comment>https://spiral.dev/docs/views-twig</comment>',
        ]
    ) {
        parent::__construct(Packages::TwigBridge, $resources, $generators, $instructions);
    }
}
