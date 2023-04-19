<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Endpoint\Job\PingHandler;
use Tests\TestCase;

final class PingCommandTest extends TestCase
{
    public function testInteractWithConsole(): void
    {
        $queue = $this->fakeQueue();

        $this->assertConsoleCommandOutputContainsStrings(
            'ping',
            ['url' => 'https://spiral.dev'],
            [
                'Pinging https://spiral.dev...',
            ],
        );

        $queue->getConnection()->assertPushed(PingHandler::class, static function (array $data): bool {
            return $data['payload']->url === 'https://spiral.dev';
        });

    }
}
