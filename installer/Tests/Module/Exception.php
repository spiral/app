<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Exception\Generator\Skeleton;

final class Exception extends AbstractModule
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

        return [
            'resources/app/src/Application/Exception/Handler.php' => 'src/Application/Exception/Handler.php',
            'resources/app/app.php' => 'src',
        ];
    }
}
