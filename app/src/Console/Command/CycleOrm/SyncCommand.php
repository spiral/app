<?php

declare(strict_types=1);

namespace App\Console\Command\CycleOrm;

use App\Bootloader\SchemaBootloader;
use Cycle\Schema\Generator\SyncTables;
use Cycle\Schema\Registry;
use Spiral\Boot\MemoryInterface;
use Spiral\Command\Cycle\Generator\ShowChanges;
use Spiral\Console\Command;
use Spiral\Cycle\SchemaCompiler;

final class SyncCommand extends Command
{
    protected const NAME = 'cycle:sync';
    protected const DESCRIPTION = 'Sync Cycle ORM schema with database without intermediate migration (risk operation)';

    public function perform(
        SchemaBootloader $bootloader,
        Registry $registry,
        MemoryInterface $memory
    ): void {
        $show = new ShowChanges($this->output);

        $schemaCompiler = SchemaCompiler::compile(
            $registry,
            array_merge($bootloader->getGenerators(), [$show, new SyncTables()])
        );
        $schemaCompiler->toMemory($memory);

        if ($show->hasChanges()) {
            $this->writeln("\n<info>ORM Schema has been synchronized</info>");
        }
    }
}
