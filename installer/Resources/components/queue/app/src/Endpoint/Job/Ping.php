<?php

declare(strict_types=1);

namespace App\Endpoint\Job;

use App\Endpoint\Console\PingCommand;
use Spiral\Queue\JobHandler;
use Psr\Log\LoggerInterface;

/**
 * Simple job handler that will be invoked by a queue consumer.
 * To push a job to a queue use {@see PingCommand} console command.
 *
 * Or use {@see Spiral\Queue\QueueInterface} directly.
 * (Spiral\Queue\QueueInterface)->push(PingJob::class, ["site"=>"http://site.com"]);
 */
final class Ping extends JobHandler
{
    public function invoke(LoggerInterface $logger, string $site): void
    {
        $logger->info('Ping job invoked', ['site' => $site]);
    }
}
