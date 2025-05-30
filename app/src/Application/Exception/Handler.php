<?php

declare(strict_types=1);

namespace App\Application\Exception;

use Spiral\Exceptions\ExceptionHandler;
use Spiral\Exceptions\Renderer\ConsoleRenderer;

/**
 * In this class you can override default exception handler behavior,
 * or add custom renderers or reporters.
 */
final class Handler extends ExceptionHandler
{
    #[\Override]
    protected function bootBasicHandlers(): void
    {
        parent::bootBasicHandlers();
        $this->addRenderer(new ConsoleRenderer());
        // $this->addRenderer(new CustomRenderer());
        // $this->addReporter(new CustomReporter());
    }
}
