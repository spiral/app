<?php

declare(strict_types=1);

namespace App\Infrastructure\CycleORM\Typecaster;

use Cycle\Database\DatabaseInterface;
use Cycle\ORM\Parser\CastableInterface;
use Cycle\ORM\Parser\UncastableInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UuidTypecast implements CastableInterface, UncastableInterface
{
    private array $rules = [];

    public function __construct(
        private readonly DatabaseInterface $database,
    ) {}

    #[\Override]
    public function setRules(array $rules): array
    {
        foreach ($rules as $key => $rule) {
            if ($rule === 'uuid') {
                unset($rules[$key]);
                $this->rules[$key] = $rule;
            }
        }

        return $rules;
    }

    #[\Override]
    public function cast(array $data): array
    {
        foreach ($this->rules as $column => $_) {
            if (!isset($data[$column])) {
                continue;
            }

            $data[$column] = Uuid::fromString($data[$column]);
        }

        return $data;
    }

    #[\Override]
    public function uncast(array $data): array
    {
        foreach ($this->rules as $column => $_) {
            if (!isset($data[$column]) || !$data[$column] instanceof UuidInterface) {
                continue;
            }

            $data[$column] = $data[$column]->toString();
        }

        return $data;
    }
}
