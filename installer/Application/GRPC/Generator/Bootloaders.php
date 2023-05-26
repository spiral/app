<?php

declare(strict_types=1);

namespace Installer\Application\GRPC\Generator;

use App\Application\Bootloader\AppBootloader;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
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

        $context->kernel->app->addGroup(
            bootloaders: [
                AppBootloader::class,
            ],
            comment: 'Application domain',
            priority: 1
        );
    }
}
