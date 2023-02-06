<?php

declare(strict_types=1);

namespace Installer\Package;

use Installer\Generator\GeneratorInterface;
use Installer\Package\Generator\SentryBridge\Bootloaders;
use Installer\Package\Generator\SentryBridge\Env;

final class SentryBridge extends Package
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        array $resources = [],
        array $generators = [
            new Bootloaders(),
            new Env(),
        ],
        array $instructions = [
            'Configure the <comment>`SENTRY_DSN`</comment> environment variable to enable Sentry error reporting.',
            'Documentation: <comment>https://spiral.dev/docs/basics-errors</comment>',
        ]
    ) {
        parent::__construct(Packages::SentryBridge, $resources, $generators, $instructions);
    }
}
