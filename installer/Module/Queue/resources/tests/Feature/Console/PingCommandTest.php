<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use Tests\TestCase;

final class PingCommandTest extends TestCase
{
    public function testInteractWithConsole(): void
    {
        $this->assertConsoleCommandOutputContainsStrings(
            'ping',
            ['site' => 'https://spiral.dev'],
            [
                'Pinging https://spiral.dev...',
            ],
        );
    }
}
