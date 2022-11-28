<?php

declare(strict_types=1);

namespace Installer\Package\Generator\SpiralValidator;

use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Spiral\Bootloader\Security\FiltersBootloader;
use Spiral\Validation\Bootloader\ValidationBootloader;
use Spiral\Validator\Bootloader\ValidatorBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(ValidationBootloader::class);
        $context->kernel->addUse(ValidatorBootloader::class);

        $context->kernel->loadAppend(ValidationBootloader::class, FiltersBootloader::class);
        $context->kernel->loadAppend(ValidatorBootloader::class, ValidationBootloader::class);
    }
}
