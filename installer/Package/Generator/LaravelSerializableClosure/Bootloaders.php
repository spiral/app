<?php

declare(strict_types=1);

namespace Installer\Package\Generator\LaravelSerializableClosure;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Serializer\Bootloader\SerializerBootloader as SpiralSerializerBootloader;
use Spiral\SerializableClosure\Bootloader\SerializableClosureBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(SpiralSerializerBootloader::class);
        $context->kernel->addUse(SerializableClosureBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                SpiralSerializerBootloader::class,
                SerializableClosureBootloader::class
            ],
            comment: 'Serializer',
            priority: 14
        );
    }
}
