<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace App\Job;

use Spiral\Jobs\JobHandler;

/**
 * (QueueInterface)->push(PingJob::class, ["value"=>"my value"]);
 */
class Ping extends JobHandler
{
    /**
     * @param string $id
     * @param string $value
     */
    public function invoke(string $id, string $value): void
    {
        // do something
        error_log("pong by {$id}, value `{$value}`");
    }
}
