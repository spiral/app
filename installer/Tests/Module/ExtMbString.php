<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Module\ExtMbString\Package;

final class ExtMbString extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }
}
