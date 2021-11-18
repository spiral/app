<?php

declare(strict_types=1);

use Cycle\ORM\SchemaInterface;

return [
    'schema' => [
        'defaults' => [
            SchemaInterface::MAPPER => \Cycle\ORM\Mapper\Mapper::class,
            // SchemaInterface::MAPPER => \Cycle\ORM\Mapper\PromiseMapper::class,

            // SchemaInterface::REPOSITORY => \App\Repository::class,
            // SchemaInterface::TYPECAST_HANDLER => [
            //    \Cycle\ORM\Parser\Typecast::class
            //],
        ]
    ],
];
