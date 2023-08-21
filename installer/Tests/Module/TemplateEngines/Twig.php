<?php

declare(strict_types=1);

namespace Tests\Module\TemplateEngines;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\TemplateEngines\Twig\Package;
use Tests\Module\AbstractModule;
use Spiral\Twig\Bootloader\TwigBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

final class Twig extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            ViewsBootloader::class,
            TwigBootloader::class,
        ];
    }
}
