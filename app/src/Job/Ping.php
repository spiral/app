<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Job;

use Spiral\Queue\JobHandler;

/**
 * (QueueInterface)->push(PingJob::class, ["value"=>"my value"]);
 */
class Ping extends JobHandler
{
    public function invoke(string $value): void
    {
        // do something
        echo \sprintf('PONG: %s', $value);
    }
}
