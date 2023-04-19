<?php

declare(strict_types=1);

namespace App\Endpoint\Job;

use App\Endpoint\Console\Site;
use Psr\Log\LoggerInterface;
use Spiral\Queue\JobHandler;

/**
 * (QueueInterface)->push('ping', ["url" => "http://site.com"]);
 */
final class PingHandler extends JobHandler
{
    public function invoke(LoggerInterface $logger, Site $payload): void
    {
        $logger->debug('Ping site', ['url' => $payload->url]);
    }
}
