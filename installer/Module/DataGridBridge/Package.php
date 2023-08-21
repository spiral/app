<?php

declare(strict_types=1);

namespace Installer\Module\DataGridBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Package as BasePackage;
use Installer\Module\DataGridBridge\Generator\Bootloaders;
use Installer\Module\DataGridBridge\Generator\Interceptors;

final class Package extends BasePackage
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [],
        array $generators = [
            new Bootloaders(),
            new Interceptors(),
        ],
        array $instructions = [
            'Documentation: <comment>https://spiral.dev/docs/component-data-grid</comment>',
        ],
    ) {
        parent::__construct(ComposerPackages::DataGridBridge, $resources, $generators, $instructions);
    }
}
