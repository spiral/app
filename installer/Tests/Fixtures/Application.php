<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Installer\Internal\Application\AbstractApplication;

final class Application extends AbstractApplication
{
    protected function getDefaultReadme(): array
    {
        return [
            'Some default instruction',
        ];
    }
}
