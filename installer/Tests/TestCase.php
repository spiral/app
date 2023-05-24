<?php

declare(strict_types=1);

namespace Tests;

use Installer\Internal\Configurator\ResourceQueue;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    protected function assertResourceQueueContains(ResourceQueue $queue, string $source, string $destination): void
    {
        $contains = false;
        foreach ($queue as $task) {
            if ((string)$task === "$source -> $destination") {
                $contains = true;
                break;
            }
        }

        $this->assertTrue($contains, "ResourceQueue does not contain $source -> $destination");
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        \Mockery::close();
    }
}
