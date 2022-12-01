<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Bootloader\Http;
use Spiral\Bootloader\Security;

final class WebApplicationBootloaders implements GeneratorInterface
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
            priority: 6
        );
    }
}
