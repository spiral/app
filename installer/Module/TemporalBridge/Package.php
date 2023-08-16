<?php

declare(strict_types=1);

namespace Installer\Module\TemporalBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Internal\Readme\Block\ListBlock;
use Installer\Internal\Readme\Section;
use Installer\Module\TemporalBridge\Generator\Bootloaders;
use Installer\Module\TemporalBridge\Generator\Env;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::TemporalBridge,
            resources: [
                '' => '',
            ],
            generators: [
                new Bootloaders(),
                new Env(),
            ],
        );
    }

    public function getReadme(): array
    {
        return [
            Section::Configuration->value => [
                new ListBlock([
                    'Documentation: https://spiral.dev/docs/temporal-configuration',
                ], $this->getTitle())
            ]
        ];
    }
}
