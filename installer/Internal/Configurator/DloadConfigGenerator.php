<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

/**
 * Builds the `dload.xml` that lists the binaries of the application: the RoadRunner server
 * and, for gRPC, the tools `grpc:generate` runs.
 */
final class DloadConfigGenerator
{
    private const SOFTWARE = [
        'rr' => '^3.0',
    ];

    /**
     * The protoc major must match the installed `google/protobuf` (5.36 → protoc 36.x).
     */
    private const GRPC_SOFTWARE = [
        'buf' => '^1.50',
        'protoc' => '^36.0',
        'protoc-gen-php-grpc' => '^3.0',
    ];

    /**
     * @param string[] $plugins RoadRunner plugins used by the application
     *
     * @return array<non-empty-string, non-empty-string> Software alias => version constraint
     */
    public function software(array $plugins): array
    {
        return \in_array('grpc', $plugins, true)
            ? self::SOFTWARE + self::GRPC_SOFTWARE
            : self::SOFTWARE;
    }

    /**
     * @param array<non-empty-string, non-empty-string> $software
     */
    public function render(array $software): string
    {
        $actions = '';
        foreach ($software as $name => $version) {
            $actions .= \sprintf("        <download software=\"%s\" version=\"%s\"/>\n", $name, $version);
        }

        return <<<XML
            <?xml version="1.0"?>
            <dload xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                   xsi:noNamespaceSchemaLocation="vendor/internal/dload/dload.xsd"
                   temp-dir="./runtime">
                <actions>
            {$actions}    </actions>
            </dload>

            XML;
    }
}
