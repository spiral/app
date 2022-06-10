<?php

declare(strict_types=1);

namespace App\Exception;

use Spiral\Exceptions\ExceptionHandler;
use Spiral\Exceptions\Renderer\ConsoleRenderer;
use Spiral\Exceptions\Renderer\JsonRenderer;
use Spiral\Exceptions\Reporter\SnapshotterReporter;

final class Handler extends ExceptionHandler
{
    public function __construct(SnapshotterReporter $reporter)
    {
        parent::__construct();

        // $this->addRenderer(new ConsoleRenderer());
        $this->addRenderer(new CollisionRenderer());
        $this->addRenderer(new JsonRenderer());

        $this->addReporter($reporter);
    }
}
