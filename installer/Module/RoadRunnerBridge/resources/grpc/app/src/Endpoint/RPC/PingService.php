<?php

declare(strict_types=1);

namespace App\Endpoint\RPC;

use Google\Protobuf\Timestamp;
use GRPC\Ping\PingRequest;
use GRPC\Ping\PingResponse;
use GRPC\Ping\PingServiceInterface;
use Psr\Log\LoggerInterface;
use Spiral\RoadRunner\GRPC;

/**
 * gRPC Ping service implementation.
 *
 * @link https://spiral.dev/docs/grpc-configuration
 */
final class PingService implements PingServiceInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function PingUrl(GRPC\ContextInterface $ctx, PingRequest $in): PingResponse
    {
        $this->logger->info('PingUrl', [
            'url' => $in->getUrl(),
        ]);

        $createdAt = new Timestamp();
        $createdAt->fromDateTime(new \DateTime());

        return new PingResponse([
            'status' => rand(0, 1) === 1 ? 200 : 500,
            'created_at' => $createdAt,
        ]);
    }
}
