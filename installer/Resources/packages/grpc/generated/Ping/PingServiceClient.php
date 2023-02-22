<?php

declare(strict_types=1);

namespace GRPC\Ping;

use Spiral\Core\InterceptableCore;
use Spiral\RoadRunner\GRPC\ContextInterface;

class PingServiceClient implements PingServiceInterface
{
    public function __construct(public InterceptableCore $core)
    {
    }

    public function PingUrl(ContextInterface $ctx, PingRequest $in): PingResponse
    {
        [$response, $status] = $this->core->callAction(PingServiceInterface::class, '/' . self::NAME . '/PingUrl', [
            'in' => $in,
            'ctx' => $ctx,
            'responseClass' => \GRPC\Ping\PingResponse::class,
        ]);

        return $response;
    }
}
