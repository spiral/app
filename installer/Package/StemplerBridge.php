<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\StemplerBridge\Bootloaders;

final class StemplerBridge extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [
            'packages/stempler/config' => 'app/config',
            'packages/stempler/views' => 'app/views',
        ],
        array $generators = [
            new Bootloaders(),
        ]
    ) {
        parent::__construct(Packages::StemplerBridge, $resources, $generators);
    }
}
