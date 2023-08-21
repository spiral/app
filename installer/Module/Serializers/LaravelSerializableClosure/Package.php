<?php

declare(strict_types=1);

namespace Installer\Module\Serializers\LaravelSerializableClosure;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::LaravelSerializableClosure,
            generators: [
                new Generator\Bootloaders(),
                new Generator\Env(),
            ],
        );
    }
}
