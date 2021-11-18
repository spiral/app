<?php

declare(strict_types=1);

namespace App\Bootloader;

use Cycle\Annotated;
use Spiral\Attributes\Factory;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\TokenizerBootloader;
use Spiral\Tokenizer\ClassesInterface;

final class AnnotatedBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        SchemaBootloader::class,
        TokenizerBootloader::class,
    ];

    protected const BINDINGS = [
        Annotated\Embeddings::class => [self::class, 'initEmbeddings'],
        Annotated\Entities::class => [self::class, 'initEntities'],
        Annotated\MergeColumns::class => [self::class, 'initMergeColumns'],
        Annotated\TableInheritance::class => [self::class, 'initTableInheritance'],
        Annotated\MergeIndexes::class => [self::class, 'initMergeIndexes'],
    ];

    public function boot(SchemaBootloader $schema): void
    {
        // AnnotationRegistry::registerLoader('class_exists');

        $schema->addGenerator(SchemaBootloader::GROUP_INDEX, Annotated\Embeddings::class);
        $schema->addGenerator(SchemaBootloader::GROUP_INDEX, Annotated\Entities::class);
        $schema->addGenerator(SchemaBootloader::GROUP_INDEX, Annotated\MergeColumns::class);
        $schema->addGenerator(SchemaBootloader::GROUP_RENDER, Annotated\TableInheritance::class);
        $schema->addGenerator(SchemaBootloader::GROUP_RENDER, Annotated\MergeIndexes::class);
    }

    private function initEmbeddings(ClassesInterface $classes): Annotated\Embeddings
    {
        return new Annotated\Embeddings(
            $classes, (new Factory)->create()
        );
    }

    public function initEntities(ClassesInterface $classes): Annotated\Entities
    {
        return new Annotated\Entities(
            $classes, (new Factory)->create()
        );
    }

    public function initMergeColumns(ClassesInterface $classes): Annotated\MergeColumns
    {
        return new Annotated\MergeColumns((new Factory)->create());
    }

    public function initTableInheritance(ClassesInterface $classes): Annotated\TableInheritance
    {
        return new Annotated\TableInheritance((new Factory)->create());
    }

    public function initMergeIndexes(ClassesInterface $classes): Annotated\MergeIndexes
    {
        return new Annotated\MergeIndexes((new Factory)->create());
    }
}

