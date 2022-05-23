<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Job\Ping;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Queue\QueueInterface;

class HomeController
{
    use PrototypeTrait;

    public function __construct(
        private readonly QueueInterface $queue,
    ) {
    }

    public function index(): string
    {
        return $this->views->render('home.dark.php');
    }

    /**
     * Example of exception page.
     *
     * @throws \Error
     */
    public function exception(): void
    {
        echo $undefined;
    }

    public function ping(): string
    {
        $jobID = $this->queue->push(Ping::class, [
            'value' => 'hello world',
        ]);

        return sprintf('Job ID: %s', $jobID);
    }
}
