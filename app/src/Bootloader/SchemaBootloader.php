<?php

declare(strict_types=1);

namespace App\Bootloader;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\GeneratorInterface;
use Cycle\Schema\Registry;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\MemoryInterface;
use Spiral\Bootloader\TokenizerBootloader;
use Spiral\Core\Container;
use Spiral\Cycle\SchemaCompiler;
use Cycle\Schema\Generator;

final class SchemaBootloader extends Bootloader implements Container\SingletonInterface
{
    public const GROUP_INDEX = 'index';
    public const GROUP_RENDER = 'render';
    public const GROUP_POSTPROCESS = 'postprocess';

    protected const DEPENDENCIES = [
        TokenizerBootloader::class,
    ];

    protected const BINDINGS = [
        SchemaInterface::class => [self::class, 'schema'],
    ];

    private Container $container;

    /** @var string[][]|GeneratorInterface[][] */
    private array $generators = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->generators = [
            self::GROUP_INDEX => [
                // find available entities
            ],
            self::GROUP_RENDER => [
                // render tables and relations
                Generator\ResetTables::class,
                Generator\GenerateRelations::class,
                Generator\ValidateEntities::class,
                Generator\RenderTables::class,
                Generator\RenderRelations::class,
            ],
            self::GROUP_POSTPROCESS => [
                // post processing
                Generator\GenerateTypecast::class,
            ],
        ];
    }

    public function addGenerator(string $group, string $generator): void
    {
        $this->generators[$group][] = $generator;
    }

    /**
     * @return GeneratorInterface[]
     * @throws \Throwable
     */
    public function getGenerators(): array
    {
        $result = [];
        foreach ($this->generators as $group) {
            foreach ($group as $generator) {
                if (is_object($generator) && !$generator instanceof Container\Autowire) {
                    $result[] = $generator;
                } else {
                    $result[] = $this->container->get($generator);
                }
            }
        }

        return $result;
    }

    /**
     * @throws \Throwable
     */
    protected function schema(MemoryInterface $memory): SchemaInterface
    {
        $schemaCompiler = SchemaCompiler::fromMemory($memory);
        if ($schemaCompiler->isEmpty()) {
            $schemaCompiler = SchemaCompiler::compile(
                $this->container->get(Registry::class),
                $this->getGenerators()
            );

            $schemaCompiler->toMemory($memory);
        }

        return $schemaCompiler->toSchema();
    }
}

