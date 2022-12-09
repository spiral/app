<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\TemporalBridge\Bootloaders;
use Installer\Package\Generator\TemporalBridge\Env;

final class TemporalBridge extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [
            'packages/temporal' => '',
        ],
        array $generators = [
            new Bootloaders(),
            new Env(),
        ],
        array $instructions = [
            'Documentation: <comment>https://spiral.dev/docs/packages-temporal-bridge</comment>'
        ]
    ) {
        parent::__construct(Packages::TemporalBridge, $resources, $generators, $instructions);
    }
}
