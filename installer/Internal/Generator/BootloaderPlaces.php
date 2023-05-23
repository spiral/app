<?php

declare(strict_types=1);

namespace Installer\Internal\Generator;

enum BootloaderPlaces: string
{
    case System = 'SYSTEM';
    case Load = 'LOAD';
    case App = 'APP';
}
