<?php

declare(strict_types=1);

namespace App\Console\Command\Migrate;

use Spiral\Console\Console;
use Symfony\Component\Console\Input\InputOption;

final class ReplayCommand extends AbstractCommand
{
    protected const NAME = 'migrate:replay';
    protected const DESCRIPTION = 'Replay (down, up) one or multiple migrations';
    protected const OPTIONS = [
        ['all', 'a', InputOption::VALUE_NONE, 'Replay all migrations.'],
    ];

    /**
     * @param  Console  $console
     * @throws \Throwable
     */
    public function perform(Console $console): void
    {
        if (!$this->verifyEnvironment()) {
            //Making sure we can safely migrate in this environment
            return;
        }

        $rollback = ['--force' => true];
        $migrate = ['--force' => true];

        if ($this->option('all')) {
            $rollback['--all'] = true;
        } else {
            $migrate['--one'] = true;
        }

        $this->writeln('Rolling back executed migration(s)...');
        $console->run('migrate:rollback', $rollback, $this->output);

        $this->writeln('');

        $this->writeln('Executing outstanding migration(s)...');
        $console->run('migrate', $migrate, $this->output);
    }
}
