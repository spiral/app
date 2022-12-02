<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Bootloader\Security;

final class GRPCApplicationBootloaders implements GeneratorInterface
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
    }
}
