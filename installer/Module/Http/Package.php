<?php

declare(strict_types=1);

namespace Installer\Module\Http;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Module\Http\Generator\Bootloaders;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::Http,
            generators: [
                new Bootloaders(),
            ],
        );
    }
}
