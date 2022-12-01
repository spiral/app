<?php

declare(strict_types=1);

namespace Installer\Generator;

interface GeneratorInterface
{
    public function process(Context $context): void;
}
