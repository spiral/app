<?php

declare(strict_types=1);

namespace App\Console\Command\CycleOrm;

use App\Bootloader\SchemaBootloader;
use Cycle\Schema\Registry;
use Spiral\Boot\MemoryInterface;
use Spiral\Console\Command;
use Spiral\Cycle\SchemaCompiler;

final class UpdateCommand extends Command
{
    protected const NAME = 'cycle';
    protected const DESCRIPTION = 'Update (init) cycle schema from database and annotated classes';

    public function perform(
        SchemaBootloader $bootloader,
        Registry $registry,
        MemoryInterface $memory
    ): void {
        $this->write('Updating ORM schema... ');

        $schemaCompiler = SchemaCompiler::compile($registry, $bootloader->getGenerators());
        $schemaCompiler->toMemory($memory);

        $this->writeln('<info>done</info>');
    }
}
