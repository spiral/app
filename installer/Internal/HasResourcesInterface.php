<?php

declare(strict_types=1);

namespace Installer\Internal;

interface HasResourcesInterface
{
    public function getResourcesPath(): string;
}
