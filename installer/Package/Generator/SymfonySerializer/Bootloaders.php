<?php

declare(strict_types=1);

namespace Installer\Package\Generator\SymfonySerializer;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Serializer\Bootloader\SerializerBootloader as SpiralSerializerBootloader;
use Spiral\Serializer\Symfony\Bootloader\SerializerBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(SpiralSerializerBootloader::class);
        $context->kernel->addUse(SerializerBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                SpiralSerializerBootloader::class,
                SerializerBootloader::class,
            ],
            comment: 'Serializer',
            priority: 14
        );
    }
}
