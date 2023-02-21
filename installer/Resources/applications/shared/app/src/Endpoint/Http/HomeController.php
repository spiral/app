<?php

declare(strict_types=1);

namespace App\Endpoint\Http;

use App\Endpoint\Job\Ping;
use Exception;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Queue\QueueInterface;
use Spiral\Router\Annotation\Route;

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

    #[Route(route: '/', name: 'index')]
    public function index(): string
    {
        return $this->views->render('home');
    }

    /**
     * Example of exception page.
     */
    #[Route(route: '/exception', name: 'exception')]
    public function exception(): never
    {
        throw new Exception('This is a test exception.');
    }

    #[Route(route: '/ping', name: 'ping')]
    public function ping(): string
    {
        $jobID = $this->queue->push(Ping::class, [
            'value' => 'hello world',
        ]);

        return sprintf('Job ID: %s', $jobID);
    }
}
