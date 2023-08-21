<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Serializers\LaravelSerializableClosure\Package;
use Spiral\SerializableClosure\Bootloader\SerializableClosureBootloader;
use Spiral\Serializer\Bootloader\SerializerBootloader;

final class LaravelSerializableClosure extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            SerializerBootloader::class,
            SerializableClosureBootloader::class,
        ];
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        return [
            'DEFAULT_SERIALIZER_FORMAT' => 'closure',
        ];
    }
}
