<?php

declare(strict_types=1);

namespace Installer\Internal;

use Installer\Internal\Application\ApplicationInterface;

final class Config
{
    /**
     * @var array{
     *     app: ApplicationInterface[],
     *     directories: array<non-empty-string, non-empty-string>
     * }
     */
    private array $config;

    /**
     * @param non-empty-string $path
     */
    public function __construct(
        private readonly string $path,
    ) {
        $this->config = require $this->path;
    }

    /**
     * @return ApplicationInterface[]
     */
    public function getApplications(): array
    {
        return $this->config['app'];
    }

    public function getApplication(int $type): ApplicationInterface
    {
        if (!isset($this->config['app'][$type])) {
            throw new \InvalidArgumentException('Invalid application type!');
        }

        return $this->config['app'][$type];
    }

    /**
     * @return array<non-empty-string, non-empty-string>
     */
    public function getDirectories(): array
    {
        $appDirectories = [];

        foreach ($this->getApplications() as $application) {
            $appDirectories[':' . \strtolower($application->getName()) . ':'] = \rtrim($application->getResourcesPath(), '/');
        }

        return \array_merge($this->config['directories'], $appDirectories);
    }

    /**
     * @return non-empty-string[]
     */
    public function getRequireDevPackages(): array
    {
        return $this->config['require-dev'] ?? [];
    }

    public function definedRequireDevPackage(string $package): bool
    {
        return \in_array($package, $this->getRequireDevPackages(), true);
    }
}
