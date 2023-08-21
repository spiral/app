<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Serializers\SymfonySerializer\Package;
use Spiral\Serializer\Bootloader\SerializerBootloader as SpiralSerializerBootloader;
use Spiral\Serializer\Symfony\Bootloader\SerializerBootloader;

final class SymfonySerializer extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            SpiralSerializerBootloader::class,
            SerializerBootloader::class,
        ];
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        return [
            'DEFAULT_SERIALIZER_FORMAT' => 'json # csv, xml, yaml',
        ];
    }
}
