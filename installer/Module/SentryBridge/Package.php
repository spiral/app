<?php

declare(strict_types=1);

namespace Installer\Module\SentryBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Internal\Readme\Block\ListBlock;
use Installer\Internal\Readme\Section;
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
        );
    }

    public function getReadme(): array
    {
        return [
            Section::Configuration->value => [
                new ListBlock([
                    'Configure the `SENTRY_DSN` environment variable to enable Sentry error reporting.',
                    'Documentation: https://spiral.dev/docs/basics-errors',
                ], $this->getTitle()),
            ],
        ];
    }
}
