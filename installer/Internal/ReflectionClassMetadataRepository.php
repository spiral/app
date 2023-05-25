<?php

declare(strict_types=1);

namespace Installer\Internal;

final class ReflectionClassMetadataRepository implements ClassMetadataRepositoryInterface
{
    public function getMetaData(string $class): ClassMetadataInterface
    {
        return new ReflectionClassMetadata($class);
    }
}
