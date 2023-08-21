<?php

declare(strict_types=1);

namespace Installer\Module\Translator;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Internal\Readme\Block\ListBlock;
use Installer\Internal\Readme\Section;
use Installer\Module\Translator\Generator\Bootloaders;
use Installer\Module\Translator\Generator\Env;
use Installer\Module\Translator\Generator\Middlewares;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::Translator,
            resources: [
                'config' => 'app/config',
                'locale' => 'app/locale',
                'src' => 'app/src',
            ],
            generators: [
                new Bootloaders(),
                new Env(),
                new Middlewares(),
            ],
        );
    }

    public function getReadme(): array
    {
        return [
            Section::Configuration->value => [
                new ListBlock([
                    'Documentation: https://spiral.dev/docs/advanced-i18n',
                ], $this->getTitle()),
            ],
        ];
    }
}
