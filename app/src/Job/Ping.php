<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
