<?php

declare(strict_types=1);

namespace Installer\Question\Option;

abstract class AbstractOption implements OptionInterface
{
    public function __construct(
        private readonly string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
