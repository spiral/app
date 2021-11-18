<?php

declare(strict_types=1);

namespace App\Cycle;

use Cycle\ORM\Mapper\PromiseMapper;
use Cycle\Schema\GeneratorInterface;
use Cycle\Schema\Registry;

class FinalClassMapperFixerGenerator implements GeneratorInterface
{
    public function run(Registry $registry): Registry
    {
        // Final class can only work with PromiseMapper
        foreach ($registry as $entity) {
            if (!$entity->getClass()) {
                continue;
            }


            $class = new \ReflectionClass($entity->getClass());
            if ($class->isFinal() && !$entity->getMapper()) {
                $entity->setMapper(PromiseMapper::class);
            }
        }

        return $registry;
    }
}
