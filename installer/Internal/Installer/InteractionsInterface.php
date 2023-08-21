<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Installer\Internal\Application\ApplicationInterface;

interface InteractionsInterface
{
    public function requestApplicationType(): int;

    public function promptForOptionalPackages(ApplicationInterface $application): \Generator;
}
