<?php

declare(strict_types=1);

namespace Tests;

use Composer\Json\JsonFile;
use Installer\Internal\Config;
use Installer\Internal\Configurator\ResourceQueue;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Spiral\Files\Files;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    protected function assertResourceQueueContains(ResourceQueue $queue, string $source, string $destination): void
    {
        $contains = false;
        foreach ($queue as $task) {
            if ($task->getFullSource() === $source && $task->getFullDestination() === $destination) {
                $contains = true;
                break;
            }
        }

        $this->assertTrue($contains, "ResourceQueue does not contain $source -> $destination");
    }

    protected function getAppPath(): string
    {
        return __DIR__ . '/App';
    }

    protected function getConfig(): Config
    {
        return new Config(__DIR__ . '/../config.php');
    }

    protected function getComposerJson(): array
    {
        $json = new JsonFile(__DIR__ . '/Fixtures/composer.json');

        return $json->read();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        \Mockery::close();
    }
}
