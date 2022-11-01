<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\ErrorHandler\ViewRenderer;
use App\Exception\CollisionRenderer;
use Spiral\Boot\AbstractKernel;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Exceptions\ExceptionHandler;
use Spiral\Exceptions\Renderer\JsonRenderer;
use Spiral\Exceptions\Reporter\FileReporter;
use Spiral\Exceptions\Reporter\LoggerReporter;
use Spiral\Http\ErrorHandler\RendererInterface;
use Spiral\Http\Middleware\ErrorHandlerMiddleware\EnvSuppressErrors;
use Spiral\Http\Middleware\ErrorHandlerMiddleware\SuppressErrorsInterface;
use Spiral\Ignition\IgnitionRenderer;

final class ExceptionHandlerBootloader extends Bootloader
{
    protected const BINDINGS = [
        SuppressErrorsInterface::class => EnvSuppressErrors::class,
        RendererInterface::class => ViewRenderer::class,
    ];

    public function init(AbstractKernel $kernel): void
    {
        $kernel->running(static function (ExceptionHandler $handler): void {
            // $handler->addRenderer(new ConsoleRenderer());
            $handler->addRenderer(new CollisionRenderer());
            $handler->addRenderer(new JsonRenderer());
        });
    }

    public function boot(
        ExceptionHandler $handler,
        LoggerReporter $logger,
        FileReporter $files,
        ?IgnitionRenderer $ignition = null
    ): void {
        $handler->addReporter($logger);
        $handler->addReporter($files);

        if ($ignition !== null) {
            $handler->addRenderer($ignition);
        }
    }
}
