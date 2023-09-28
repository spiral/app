<?php

declare(strict_types=1);

namespace Installer\Module\Dumper;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Module\Dumper\Generator\Bootloaders;
use Installer\Module\Dumper\Generator\Middlewares;

final class Package extends BasePackage
{
    public function __construct(
        array $generators = [
            new Bootloaders(),
            new Middlewares(),
        ],
    ) {
        parent::__construct(ComposerPackages::Dumper, generators: $generators);
    }
}
