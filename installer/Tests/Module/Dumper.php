<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Dumper\Package;
use Installer\Module\Http\Package as HttpPackage;
use Spiral\Debug\Bootloader\DumperBootloader;
use Spiral\Debug\Middleware\DumperMiddleware;

final class Dumper extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            DumperBootloader::class,
        ];
    }

    public function getMiddleware(ApplicationInterface $application): array
    {
        if (!$application->isPackageInstalled(new HttpPackage())) {
            return [];
        }

        return [
            'global' => [
                DumperMiddleware::class,
            ],
        ];
    }
}
