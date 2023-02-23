<?php

declare(strict_types=1);

namespace GRPC\Config;

use Grpc\ChannelCredentials;
use Spiral\Core\InjectableConfig;

final class GRPCServicesConfig extends InjectableConfig
{
    public const CONFIG = 'grpcServices';

    /** @var array<class-string, array{host: string, credentials?: mixed}> */
    protected array $config = ['services' => []];

    public function getDefaultCredentials(): mixed
    {
        return ChannelCredentials::createInsecure();
    }

    /**
     * Get service definition.
     * @return array{host: string, credentials?: mixed}
     */
    public function getService(string $name): array
    {
        return $this->config['services'][$name] ?? [
            'host' => 'localhost',
        ];
    }
}
