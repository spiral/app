<?php

declare(strict_types=1);

namespace Installer\Package\Generator\SpiralValidator;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Bootloader\Security\FiltersBootloader;
use Spiral\Validation\Bootloader\ValidationBootloader;
use Spiral\Validator\Bootloader\ValidatorBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(ValidationBootloader::class);
        $context->kernel->addUse(ValidatorBootloader::class);

        $context->kernel->load->append(ValidationBootloader::class, FiltersBootloader::class);
        $context->kernel->load->append(ValidatorBootloader::class, ValidationBootloader::class);
    }
}
