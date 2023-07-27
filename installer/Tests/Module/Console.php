<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Console\Generator\Skeleton;

final class Console extends AbstractModule
{
    public function getGenerators(ApplicationInterface $application): array
    {
        return [
            Skeleton::class,
        ];
    }

    public function getCopiedResources(ApplicationInterface $application): array
    {
        if (!$application->hasSkeleton()) {
            return [];
        }

        return ['skeleton/app/src/Endpoint/Console/DoNothing.php' => 'src/Endpoint/Console/DoNothing.php'];
    }

    public function getResourcesPath(): ?string
    {
        return \dirname(__DIR__, 2) . '/Application/Cli/resources/';
    }
}
