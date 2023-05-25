<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Configurator;

use Installer\Internal\Configurator\ResourceQueue;
use Tests\TestCase;

final class ResourceQueueTest extends TestCase
{
    public function testAfterIterationTasksShouldBeDeleted(): void
    {
        $resourceQueue = new ResourceQueue('test_source_root');

        $resourceQueue->copy('test_source', 'test_destination');
        $this->assertCount(1, $resourceQueue);

        \iterator_to_array($resourceQueue);

        $this->assertCount(0, $resourceQueue);
    }

    public function testSourceRootShouldBeAddToSourcePath(): void
    {
        $resourceQueue = new ResourceQueue('test_source_root');

        $resourceQueue->copy('test_source', 'test_destination');

        $this->assertResourceQueueContains(
            $resourceQueue,
            '/test_source_root/test_source',
            '/test_destination'
        );
    }

    public function testSourceRootCanBeChanged(): void
    {
        $resourceQueue = new ResourceQueue('test_source_root');

        $resourceQueue->copy('test_source', 'test_destination');
        $resourceQueue->setSourceRoot('test_source_root_2');
        $resourceQueue->copy('test_source', 'test_destination');

        $this->assertResourceQueueContains(
            $resourceQueue,
            '/test_source_root/test_source',
            '/test_destination'
        );

        $this->assertResourceQueueContains(
            $resourceQueue,
            '/test_source_root_2/test_source',
            '/test_destination'
        );
    }
}
