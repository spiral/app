<?php

declare(strict_types=1);

namespace Installer\Package\Generator;

use Installer\Application\ApplicationInterface;

/**
 * The current state of the application files that are available for modification by the package.
 */
final class Context
{
    public function __construct(
        public readonly ApplicationInterface $application,
        public readonly KernelConfigurator $kernel,
        public readonly string $applicationRoot,
        public readonly array $composerDefinition
    ) {
    }
}
