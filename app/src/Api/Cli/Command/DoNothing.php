<?php

declare(strict_types=1);

namespace App\Api\Cli\Command;

use Spiral\Console\Command;

class DoNothing extends Command
{
    protected const NAME = 'do-nothing';
    protected const DESCRIPTION = '';
    protected const ARGUMENTS = [];
    protected const OPTIONS = [];

    protected function perform(): void
    {
        $this->info('done');
    }
}
