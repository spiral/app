<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Generator\GeneratorInterface;

interface ModuleInterface
{
    public function getPackage(): ?string;

    /**
     * @return array<class-string<GeneratorInterface>>
     */
    public function getGenerators(ApplicationInterface $application): array;

    /**
     * @return array<class-string>
     */
    public function getBootloaders(ApplicationInterface $application): array;

    public function getCopiedResources(ApplicationInterface $application): array;

    public function getRemovedResources(ApplicationInterface $application): array;

    public function getMiddleware(ApplicationInterface $application): array;

    public function getInterceptors(ApplicationInterface $application): array;

    public function getEnvironmentVariables(ApplicationInterface $application): array;

    public function getResourcesPath(): ?string;
}
