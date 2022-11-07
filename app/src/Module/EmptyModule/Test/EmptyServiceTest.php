<?php

declare(strict_types=1);

namespace App\Module\EmptyModule\Test;

use App\Module\EmptyModule\Api\EmptyService as EmptyServiceInterface;
use App\Module\EmptyModule\Internal\EmptyService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @psalm-internal App\Module\EmptyModule\Test
 */
final class EmptyServiceTest extends TestCase
{
    /**
     * The {@see EmptyService::doNothing()} does nothing.
     */
    public function testDoNothing(): void
    {
        $this->createEmptyService()->doNothing();

        $this->assertTrue(true);
    }

    private function createEmptyService(): EmptyServiceInterface
    {
        return new EmptyService();
    }
}
