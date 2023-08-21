<?php

declare(strict_types=1);

namespace Installer\Module\Serializers\SymfonySerializer;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Module\Serializers\SymfonySerializer\Generator\Bootloaders;
use Installer\Module\Serializers\SymfonySerializer\Generator\Env;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::SymfonySerializer,
            generators: [
                new Bootloaders(),
                new Env(),
            ],
        );
    }
}
