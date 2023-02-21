<?php

declare(strict_types=1);

namespace Installer\Package;

final class Queue extends Package
{
    public function __construct(
        array $generators = [
            new Bootloaders()
        ],
    ) {
        parent::__construct(Packages::YiiErrorHandler, generators: $generators);
    }
}