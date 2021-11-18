<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity]
class User
{
    #[Column(type: 'int', primary: true)]
    private int $id;

    public function __construct(
        #[Column(type: 'string', name: 'username')]
        private string $name,

        #[Column(type: 'enum(active,disabled)')]
        private string $status,

        #[Column(type: 'string(64)', nullable: true)]
        private ?string $password = null,
    ) {
    }
}
