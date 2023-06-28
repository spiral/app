<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Generator\GeneratorInterface;

interface ModuleInterface
{
    public function getPackage(): string;

    /**
     * @return array<class-string<GeneratorInterface>>
     */
    public function getGenerators(): array;

    /**
     * @return array<class-string>
     */
    public function getBootloaders(): array;

    public function getCopiedResources(): array;

    public function getRemovedResources(): array;

    public function getMiddleware(): array;

    public function getEnvironmentVariables(): array;

    public function getResourcesPath(): string;
}
