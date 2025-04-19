<?php

declare(strict_types=1);

namespace App\Endpoint\Temporal;

use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

/**
 * This is a simple ping workflow that does nothing.
 *
 * @link https://docs.temporal.io/develop/php/
 */
#[AssignWorker('my-task-queue')]
#[WorkflowInterface]
class Ping
{
    #[WorkflowMethod(name: 'ping')]
    public function handle()
    {
        return 'pong';
    }
}
