<?php

declare(strict_types=1);

namespace Installer\Module\SentryBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Module\SentryBridge\Generator\Bootloaders;
use Installer\Module\SentryBridge\Generator\Env;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::SentryBridge,
            generators: [
                new Bootloaders(),
                new Env(),
            ],
            instructions: [
                'Configure the <comment>`SENTRY_DSN`</comment> environment variable to enable Sentry error reporting.',
                'Documentation: <comment>https://spiral.dev/docs/basics-errors</comment>',
            ]
        );
    }
}
