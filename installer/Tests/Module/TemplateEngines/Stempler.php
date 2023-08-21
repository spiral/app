<?php

declare(strict_types=1);

namespace Tests\Module\TemplateEngines;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\TemplateEngines\Stempler\Package;
use Tests\Module\AbstractModule;
use Spiral\Stempler\Bootloader\StemplerBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

final class Stempler extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            ViewsBootloader::class,
            StemplerBootloader::class,
        ];
    }
}
