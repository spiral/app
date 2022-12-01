<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Package\Generator\SentryBridge\Bootloaders;

final class SentryBridge extends Package
{
    public function __construct()
    {
        parent::__construct(
            package: Packages::SentryBridge,
            generators: [
                new Bootloaders(),
            ]
        );
    }
}
