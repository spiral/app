<?php

declare(strict_types=1);

namespace Installer\Internal;

interface ClassMetadataInterface
{
    /**
     * Get the name of the class without namespace
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Get the path to the class file
     *
     * @return non-empty-string
     */
    public function getPath(): string;

    /**
     * Get the namespace of the class
     *
     * @return non-empty-string
     */
    public function getNamespace(): string;
}
