<?php

declare(strict_types=1);

namespace Installer\Module\DataGridBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Package as BasePackage;
use Installer\Internal\Readme\Block\ListBlock;
use Installer\Internal\Readme\Section;
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
    ) {
        parent::__construct(ComposerPackages::DataGridBridge, $resources, $generators);
    }

    public function getReadme(): array
    {
        return [
            Section::Configuration->value => [
                new ListBlock([
                    'Documentation: `https://spiral.dev/docs/component-data-grid`',
                ], $this->getTitle()),
            ],
        ];
    }
}
