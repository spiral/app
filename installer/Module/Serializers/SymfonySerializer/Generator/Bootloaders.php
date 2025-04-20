<?php

declare(strict_types=1);

namespace Installer\Module\Serializers\SymfonySerializer\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Serializer\Bootloader\SerializerBootloader as SpiralSerializerBootloader;
use Spiral\Serializer\Symfony\Bootloader\SerializerBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(SpiralSerializerBootloader::class);
        $context->kernel->addUse(SerializerBootloader::class, 'SymfonySerializer');

        $context->kernel->load->addGroup(
            bootloaders: [
                SpiralSerializerBootloader::class,
                SerializerBootloader::class,
            ],
            comment: 'Serializer',
            priority: 15,
        );
    }
}
