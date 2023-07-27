<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\SapiBridge\Package;
use Spiral\Sapi\Bootloader\SapiBootloader;

final class SapiBridge extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            SapiBootloader::class,
        ];
    }
}
