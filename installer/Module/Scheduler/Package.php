<?php

declare(strict_types=1);

namespace Installer\Module\Scheduler;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Module\Scheduler\Generator\Bootloaders;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::Scheduler,
            generators: [
                new Bootloaders(),
            ],
            instructions: [
                'Documentation: <comment>https://spiral.dev/docs/advanced-scheduler</comment>',
            ]
        );
    }
}
