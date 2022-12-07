<?php

declare(strict_types=1);

namespace Installer\Question\Option;

use Installer\Generator\GeneratorInterface;

final class BooleanOption extends AbstractOption
{
    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(
        string $name,
        public readonly bool $value = true,
        public readonly array $generators = []
    ) {
        parent::__construct($name);
    }
}
