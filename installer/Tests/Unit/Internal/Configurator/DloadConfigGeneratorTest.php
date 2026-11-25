<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Configurator;

use Installer\Internal\Configurator\DloadConfigGenerator;
use Tests\TestCase;

final class DloadConfigGeneratorTest extends TestCase
{
    public function testSoftwareWithoutGrpc(): void
    {
        $this->assertSame(['rr' => '^3.0'], (new DloadConfigGenerator())->software(['http', 'jobs']));
    }

    public function testSoftwareWithGrpc(): void
    {
        $this->assertSame(
            ['rr', 'buf', 'protoc', 'protoc-gen-php-grpc'],
            \array_keys((new DloadConfigGenerator())->software(['grpc'])),
        );
    }

    public function testRender(): void
    {
        $this->assertSame(
            <<<'XML'
                <?xml version="1.0"?>
                <dload xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                       xsi:noNamespaceSchemaLocation="vendor/internal/dload/dload.xsd"
                       temp-dir="./runtime">
                    <actions>
                        <download software="rr" version="^3.0"/>
                        <download software="buf" version="^1.50"/>
                    </actions>
                </dload>

                XML,
            (new DloadConfigGenerator())->render(['rr' => '^3.0', 'buf' => '^1.50']),
        );
    }
}
