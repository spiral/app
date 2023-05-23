<?php

declare(strict_types=1);

namespace Installer\Module\Validators\Symfony;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Module\Validators\Symfony\Generator\Bootloaders;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::SymfonyValidator,
            generators: [
                new Bootloaders(),
            ],
            instructions: [
                'Read more about validation in the Spiral Framework: <comment>https://spiral.dev/docs/validation-factory</comment>',
                'Documentation: <comment>https://spiral.dev/docs/validation-symfony</comment>',
            ]
        );
    }
}
