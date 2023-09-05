<?php

declare(strict_types=1);

namespace Installer\Application\Web\Generator;

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
            priority: 5
        );

        $context->kernel->load->append(Http\RouterBootloader::class, Http\HttpBootloader::class);
        $context->kernel->load->append(Http\JsonPayloadsBootloader::class, Http\RouterBootloader::class);
        $context->kernel->load->append(Http\CookiesBootloader::class, Http\JsonPayloadsBootloader::class);
        $context->kernel->load->append(Http\SessionBootloader::class, Http\CookiesBootloader::class);
        $context->kernel->load->append(Http\CsrfBootloader::class, Http\SessionBootloader::class);
        $context->kernel->load->append(Http\PaginationBootloader::class, Http\CsrfBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                RoutesBootloader::class,
            ],
            comment: 'Configure route groups, middleware for route groups',
            priority: 1000
        );

        $context->kernel->app->addGroup(
            bootloaders: [
                AppBootloader::class,
            ],
            comment: 'Application domain',
            priority: 1
        );
    }
}
