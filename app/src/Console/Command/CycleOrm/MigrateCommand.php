<?php

declare(strict_types=1);

namespace App\Console\Command\CycleOrm;

use App\Bootloader\SchemaBootloader;
use App\Console\Command\CycleOrm\Generator\ShowChanges;
use App\Console\Command\Migrate\AbstractCommand;
use Cycle\Migrations\State;
use Cycle\Schema\Generator\Migrations\GenerateMigrations;
use Cycle\Schema\Compiler;
use Cycle\Schema\Registry;
use Spiral\Boot\MemoryInterface;
use Spiral\Console\Console;
use Spiral\Cycle\SchemaCompiler;
use Cycle\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

final class MigrateCommand extends AbstractCommand
{
    protected const NAME = 'cycle:migrate';
    protected const DESCRIPTION = 'Generate ORM schema migrations';
    protected const OPTIONS = [
        ['run', 'r', InputOption::VALUE_NONE, 'Automatically run generated migration.'],
    ];

    public function perform(
        SchemaBootloader $bootloader,
        Registry $registry,
        MemoryInterface $memory,
        GenerateMigrations $migrations,
        Migrator $migrator,
        Console $console
    ): void {
        $migrator->configure();

        foreach ($migrator->getMigrations() as $migration) {
            if ($migration->getState()->getStatus() !== State::STATUS_EXECUTED) {
                $this->writeln('<fg=red>Outstanding migrations found, run `migrate` first.</fg=red>');
                return;
            }
        }

        $show = new ShowChanges($this->output);
        $schemaCompiler = SchemaCompiler::compile(
            $registry,
            array_merge($bootloader->getGenerators(), [$show])
        );

        $schemaCompiler->toMemory($memory);

        if ($show->hasChanges()) {
            (new Compiler())->compile($registry, [$migrations]);

            if ($this->option('run')) {
                $console->run('migrate', [], $this->output);
            }
        }
    }
}
