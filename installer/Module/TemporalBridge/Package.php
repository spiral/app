<?php

declare(strict_types=1);

namespace Installer\Module\TemporalBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
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
            instructions: [
                'Documentation: <comment>https://spiral.dev/docs/temporal-configuration</comment>',
            ]
        );
    }
}
