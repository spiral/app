<?php

declare(strict_types=1);

namespace Installer\Internal\Readme;

enum Section: string
{
    case NextSteps = 'Next steps';
    case Configuration = 'Configuration';
    case Usage = 'Usage';
    case ConsoleCommands = 'Console commands';
}
