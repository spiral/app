<?php

declare(strict_types=1);

namespace Installer\Module\EventDispatcher\League;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Internal\Readme\Block\ListBlock;
use Installer\Internal\Readme\Section;
use Installer\Module\EventDispatcher\League\Generator\Bootloaders;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::LeagueEvent,
            generators: [
                new Bootloaders(),
            ],
        );
    }

    #[\Override]
    public function getReadme(): array
    {
        return [
            Section::Configuration->value => [
                new ListBlock([
                    'Documentation: https://spiral.dev/docs/advanced-events',
                ], $this->getTitle()),
            ],
        ];
    }
}
