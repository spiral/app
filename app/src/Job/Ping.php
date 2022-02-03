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
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * (QueueInterface)->push(PingJob::class, ["value"=>"my value"]);
 */
class Ping extends JobHandler
{
    public function invoke(HttpClientInterface $client, string $url): void
    {
        // do something
        $status = $client->request('GET', $url)->getStatusCode() === 200;
        echo $status ? 'PONG' : 'ERROR';
    }
}
