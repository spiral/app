<?php

declare(strict_types=1);

namespace Installer\Package\Generator\CycleBridge;

enum Collections: string
{
    case Array = 'array';
    case Doctrine = 'doctrine';
    case Illuminate = 'illuminate';
    case Loophp = 'loophp';
}
