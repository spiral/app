<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Configurator;

use Installer\Internal\Events\CopyEvent;
use Tests\TestCase;

final class CopyTaskTest extends TestCase
{
    public function testItReturnsFullSource(): void
    {
        $task = new CopyEvent('source', 'destination', 'root');

        $this->assertEquals('/root/source', $task->getFullSource());
    }

    public function testItReturnsFullSourceWhenSourceRootIsNotProvided(): void
    {
        $task = new CopyEvent('source', 'destination');

        $this->assertEquals('/source', $task->getFullSource());
    }

    public function testItReturnsFullDestination(): void
    {
        $task = new CopyEvent('source', 'destination', '', 'destRoot');

        $this->assertEquals('/destRoot/destination', $task->getFullDestination());
    }

    public function testItReturnsFullSourceWhenDestinationRootIsNotProvided(): void
    {
        $task = new CopyEvent('source', 'destination');

        $this->assertEquals('/destination', $task->getFullDestination());
    }

    public function testIitCreatesACopyTaskWithSourceRoot(): void
    {
        $task = new CopyEvent('source', 'destination', 'root');
        $taskWithRoot = $task->withSourceRoot('root2');

        $this->assertEquals('/root2/source', $taskWithRoot->getFullSource());
        $this->assertEquals('/destination', $taskWithRoot->getFullDestination());
    }

    public function testItCreatesACopyTaskWithDestinationRoot(): void
    {
        $task = new CopyEvent('source', 'destination', '', 'destRoot');
        $taskWithRoot = $task->withDestinationRoot('destRoot2');

        $this->assertEquals('/source', $taskWithRoot->getFullSource());
        $this->assertEquals('/destRoot2/destination', $taskWithRoot->getFullDestination());
    }

    public function testItConvertsToString(): void
    {
        $task = new CopyEvent('source', 'destination', 'root', 'destRoot');
        $this->assertEquals('Copy [/root/source:missing] -> [/destRoot/destination]', (string) $task);
    }
}
