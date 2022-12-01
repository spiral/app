<?php

declare(strict_types=1);

namespace Installer\Package;

final class ExtGRPC extends Package
{
    public function __construct()
    {
        parent::__construct(Packages::ExtGRPC);
    }
}
