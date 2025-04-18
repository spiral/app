<?php

declare(strict_types=1);

namespace Installer\Application\Custom\Generator;

use App\Application\Bootloader\AppBootloader;
use App\Application\Bootloader\RoutesBootloader;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Bootloader\Http;
use Spiral\Bootloader\Security;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->load->addGroup(
            bootloaders: [
                Security\EncrypterBootloader::class,
                Security\FiltersBootloader::class,
                Security\GuardBootloader::class,
            ],
            comment: 'Security and validation',
            priority: 5,
        );

        $context->kernel->load->addGroup(
            bootloaders: [
                Http\RouterBootloader::class,
                Http\JsonPayloadsBootloader::class,
                Http\CookiesBootloader::class,
                Http\SessionBootloader::class,
                Http\CsrfBootloader::class,
                Http\PaginationBootloader::class,
            ],
            comment: 'HTTP extensions',
            priority: 6,
        );

        $context->kernel->load->addGroup(
            bootloaders: [
                RoutesBootloader::class,
            ],
            comment: 'Configure route groups, middleware for route groups',
            priority: 101,
        );

        $context->kernel->app->addGroup(
            bootloaders: [
                AppBootloader::class,
            ],
            comment: 'Application domain',
            priority: 1,
        );
    }
}
