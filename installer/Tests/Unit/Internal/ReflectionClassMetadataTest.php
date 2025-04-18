<?php

declare(strict_types=1);

namespace Internal;

use Installer\Internal\ReflectionClassMetadata;
use Tests\Fixtures\RoutesBootloader;
use Tests\TestCase;

final class ReflectionClassMetadataTest extends TestCase
{
    private ReflectionClassMetadata $metadata;

    public function testGetClassName(): void
    {
        $this->assertSame('Tests\Fixtures\RoutesBootloader', $this->metadata->getName());
    }

    public function testGetPath(): void
    {
        $this->assertTrue(\str_ends_with($this->metadata->getPath(), 'Tests/Fixtures/RoutesBootloader.php'));
    }

    public function testGetNamespace(): void
    {
        $this->assertSame('Tests\Fixtures', $this->metadata->getNamespace());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->metadata = new ReflectionClassMetadata(RoutesBootloader::class);
    }
}
