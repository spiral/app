<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Installer\Internal\ApplicationInterface;

interface InteractionsInterface
{
    public function requestApplicationType(): int;

    public function promptForOptionalPackages(ApplicationInterface $application): \Generator;
}
