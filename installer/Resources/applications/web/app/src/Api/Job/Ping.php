<?php

declare(strict_types=1);

namespace App\Api\Job;

use Spiral\Queue\JobHandler;

/**
 * (QueueInterface)->push(PingJob::class, ["value"=>"my value"]);
 */
class Ping extends JobHandler
{
    public function invoke(array $payload): void
    {
        // do something
        echo $payload['value'];
    }
}
