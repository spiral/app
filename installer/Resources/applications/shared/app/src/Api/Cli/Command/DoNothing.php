<?php

declare(strict_types=1);

namespace App\Api\Cli\Command;

use Spiral\Console\Command;

/**
 * To execute this command run:
 * php app.php do-nothing foo --times=20
 *
 * Run `php app.php help do-nothing` to see all available options.
 */
final class DoNothing extends Command
{
    protected const SIGNATURE = <<<CMD
        do-nothing
        {name : Task name}
        {--t|times=10 : Number of times to repeat}
        CMD;

    protected const DESCRIPTION = 'The command does nothing.';

    public function __invoke(): int
    {
        $this->info(\sprintf('I did %s %s times!', $this->argument('name'), $this->option('times')));

        return self::SUCCESS;
    }
}
