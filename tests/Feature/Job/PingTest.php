<?php

declare(strict_types=1);

namespace Tests\Feature\Job;

use App\Endpoint\Job\Ping;
use Spiral\Testing\Queue\FakeQueue;
use Tests\TestCase;

class PingTest extends TestCase
{
    private FakeQueue $connection;

    public function testJobPushed(): void
    {
        $this->connection->push(Ping::class, ['value' => 'hello world']);

        $this->connection->assertPushed(
            Ping::class,
            static fn(array $data) => $data['handler'] instanceof Ping && $data['payload']['value'] === 'hello world',
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->connection = $this->fakeQueue()->getConnection();
    }
}
