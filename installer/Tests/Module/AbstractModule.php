<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Package;

abstract class AbstractModule implements ModuleInterface
{
    public function __construct(
        protected readonly ?Package $package = null
    ) {
    }

    public function getPackage(): ?string
    {
        return $this->package?->getName();
    }

    public function getGenerators(ApplicationInterface $application): array
    {
        if ($this->package === null) {
            return [];
        }

        return \array_map(
            static fn (string|GeneratorInterface $generator): string => \is_string($generator)
                ? $generator
                : $generator::class,
            $this->package->getGenerators()
        );
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [];
    }

    public function getCopiedResources(ApplicationInterface $application): array
    {
        if ($this->package === null) {
            return [];
        }

        return $this->package->getResources();
    }

    public function getRemovedResources(ApplicationInterface $application): array
    {
        return [];
    }

    public function getMiddleware(ApplicationInterface $application): array
    {
        return [];
    }

    public function getInterceptors(ApplicationInterface $application): array
    {
        return [];
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        return [];
    }

    public function getResourcesPath(): ?string
    {
        return $this->package?->getResourcesPath();
    }
}
