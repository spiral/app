<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\DataGridBridge\Package;
use Spiral\DataGrid\Bootloader\GridBootloader;
use Spiral\DataGrid\Interceptor\GridInterceptor;

final class DataGridBridge extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            GridBootloader::class,
        ];
    }

    public function getInterceptors(ApplicationInterface $application): array
    {
        return [
            GridInterceptor::class,
        ];
    }
}
