<?php

declare(strict_types=1);

namespace Internal;

use Installer\Internal\ReflectionClassMetadataRepository;
use Tests\Fixtures\RoutesBootloader;
use Tests\TestCase;

final class ReflectionClassMetadataRepositoryTest extends TestCase
{
    public function testGetMetaData(): void
    {
        $repo = new ReflectionClassMetadataRepository();

        $this->assertSame(
            'Tests\Fixtures\RoutesBootloader',
            $repo->getMetaData(RoutesBootloader::class)->getName()
        );
    }
}
