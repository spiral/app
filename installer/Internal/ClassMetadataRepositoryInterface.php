<?php

declare(strict_types=1);

namespace Installer\Internal;

interface ClassMetadataRepositoryInterface
{
    /**
     * Get the class metadata for the given class.
     *
     * @param class-string $class
     */
    public function getMetaData(string $class): ClassMetadataInterface;
}
