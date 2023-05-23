<?php

declare(strict_types=1);

namespace Installer\Module\Validators\Laravel\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Bootloader\Security\FiltersBootloader;
use Spiral\Validation\Bootloader\ValidationBootloader;
use Spiral\Validation\Laravel\Bootloader\ValidatorBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel
            ->addUse(ValidationBootloader::class)
            ->addUse(ValidatorBootloader::class);

        $context->kernel->load
            ->append(ValidationBootloader::class, FiltersBootloader::class)
            ->append(ValidatorBootloader::class, ValidationBootloader::class);
    }
}
