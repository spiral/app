<?php

declare(strict_types=1);

namespace Installer\Question\Option;

final class BooleanOption extends AbstractOption
{
    public function __construct(
        string $name,
        public readonly bool $value = true
    ) {
        parent::__construct($name);
    }
}
