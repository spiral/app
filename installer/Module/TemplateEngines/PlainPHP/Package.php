<?php

declare(strict_types=1);

namespace Installer\Module\TemplateEngines\PlainPHP;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package as BasePackage;
use Installer\Internal\Readme\Block\ListBlock;
use Installer\Internal\Readme\Section;
use Installer\Module\TemplateEngines\PlainPHP\Generator\Bootloaders;

final class Package extends BasePackage
{
    public function __construct()
    {
        parent::__construct(
            package: ComposerPackages::Views,
            resources: [
                'views' => 'app/views',
            ],
            generators: [
                new Bootloaders(),
            ],
        );
    }

    public function getReadme(): array
    {
        return [
            Section::Configuration->value => [
                new ListBlock([
                    'Read more about views in the Spiral Framework: https://spiral.dev/docs/views-configuration',
                    'Documentation: https://spiral.dev/docs/views-plain',
                ], $this->getTitle()),
            ],
        ];
    }
}
