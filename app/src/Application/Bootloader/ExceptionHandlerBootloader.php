<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Application\Exception\Handler;
use App\Application\Exception\Renderer\ViewRenderer;
use Spiral\Boot\AbstractKernel;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Exceptions\ExceptionHandler;
use Spiral\Exceptions\ExceptionHandlerInterface;
use Spiral\Exceptions\Renderer\JsonRenderer;
use Spiral\Exceptions\Reporter\FileReporter;
use Spiral\Exceptions\Reporter\LoggerReporter;
use Spiral\Http\ErrorHandler\RendererInterface;
use Spiral\Http\Middleware\ErrorHandlerMiddleware\EnvSuppressErrors;
use Spiral\Http\Middleware\ErrorHandlerMiddleware\SuppressErrorsInterface;

final class ExceptionHandlerBootloader extends Bootloader
{
    protected const BINDINGS = [
        SuppressErrorsInterface::class => EnvSuppressErrors::class,

        // This is a renderer for the default exception handler
        // when DEBUG is set to false
        RendererInterface::class => ViewRenderer::class,
    ];

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
