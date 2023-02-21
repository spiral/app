<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Application\Exception\Handler;
use Spiral\Boot\AbstractKernel;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Exceptions\ExceptionHandler;
use Spiral\Exceptions\ExceptionHandlerInterface;
use Spiral\Exceptions\Renderer\JsonRenderer;
use Spiral\Exceptions\Reporter\FileReporter;
use Spiral\Exceptions\Reporter\LoggerReporter;

final class ExceptionHandlerBootloader extends Bootloader
{
    public function init(AbstractKernel $kernel): void
    {
        $kernel->running(static function (ExceptionHandler $handler): void {
            $handler->addRenderer(new JsonRenderer());
        });
    }

    public function boot(
        ExceptionHandlerInterface $handler,
        LoggerReporter $logger,
        FileReporter $files,
    ): void {
        \assert($handler instanceof Handler);
        $handler->addReporter($logger);
        $handler->addReporter($files);
    }
}
