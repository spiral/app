<?php

declare(strict_types=1);

namespace Installer\Module\Scheduler;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Internal\Readme\Block\ListBlock;
use Installer\Internal\Readme\Section;
use Installer\Module\Scheduler\Generator\Bootloaders;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::Scheduler,
            generators: [
                new Bootloaders(),
            ]
        );
    }

    public function getReadme(): array
    {
        return [
            Section::Configuration->value => [
                new ListBlock([
                    'Documentation: https://spiral.dev/docs/advanced-scheduler',
                ], $this->getTitle()),
            ],
        ];
    }
}
