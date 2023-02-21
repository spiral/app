<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\Mailer\Bootloaders;
use Installer\Package\Generator\Mailer\Env;

final class Mailer extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [
            'packages/mailer/config' => 'app/config',
        ],
        array $generators = [
            new Bootloaders(),
            new Env(),
        ],
        array $instructions = [
            'Configuration file: <comment>app/config/mailer.php</comment>',
            'Documentation: <comment>https://spiral.dev/docs/advanced-sendit</comment>',
        ]
    ) {
        parent::__construct(Packages::CycleBridge, $resources, $generators, $instructions);
    }
}
