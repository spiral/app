<?php

declare(strict_types=1);

namespace Installer\Package\Generator;

interface GeneratorInterface
{
    public function process(Context $context): void;
}
