<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace App\Job;

use Spiral\Jobs\AbstractJob;

/**
 * (QueueInterface)->push(new PingJob("value"));
 */
class PingJob extends AbstractJob
{
    /**
     * @param string $id
     * @param string $value
     */
    public function do(string $id, string $value)
    {
        // do something
        error_log("pong by {$id}, value `{$value}`");
    }
}