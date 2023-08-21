<?php

declare(strict_types=1);

namespace Installer\Module\ErrorHandler\Yii;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Module\ErrorHandler\Yii\Generator\Bootloaders;

final class Package extends BasePackage
{
    public function __construct(
        array $generators = [
            new Bootloaders(),
        ],
    ) {
        parent::__construct(ComposerPackages::YiiErrorHandler, generators: $generators);
    }
}
