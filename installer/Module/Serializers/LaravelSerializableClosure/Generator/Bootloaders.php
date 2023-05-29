<?php

declare(strict_types=1);

namespace Installer\Module\Serializers\LaravelSerializableClosure\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\SerializableClosure\Bootloader\SerializableClosureBootloader;
use Spiral\Serializer\Bootloader\SerializerBootloader as SpiralSerializerBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(SpiralSerializerBootloader::class);
        $context->kernel->addUse(SerializableClosureBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                SpiralSerializerBootloader::class,
                SerializableClosureBootloader::class,
            ],
            comment: 'Serializer',
            priority: 15
        );
    }
}
