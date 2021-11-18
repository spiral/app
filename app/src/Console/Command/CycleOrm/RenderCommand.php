<?php

declare(strict_types=1);

namespace App\Console\Command\CycleOrm;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\OutputSchemaRenderer;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Spiral\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

class RenderCommand extends Command
{
    protected const NAME = 'cycle:render';
    protected const DESCRIPTION = 'Render available CycleORM schemas';

    public function perform(
        OutputInterface $output,
        SchemaInterface $schema,
        OutputSchemaRenderer $renderer,
        SchemaToArrayConverter $converter
    ): int {
        $output->writeln(
            $renderer->render(
                $converter->convert($schema)
            )
        );

        return 0;
    }
}
