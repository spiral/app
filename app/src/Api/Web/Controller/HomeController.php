<?php

declare(strict_types=1);

namespace App\Api\Web\Controller;

use App\Api\Job\Ping;
use Exception;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Queue\QueueInterface;

class HomeController
{
    /**
     * Read more about Prototyping:
     * @link https://spiral.dev/docs/basics-prototype/#installation
     */
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
     */
    public function exception(): never
    {
        throw new Exception('This is a test exception.');
    }

    public function ping(): string
    {
        $jobID = $this->queue->push(Ping::class, [
            'value' => 'hello world',
        ]);

        return sprintf('Job ID: %s', $jobID);
    }
}
