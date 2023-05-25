<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Bootloader;

use App\Application\Bootloader\ExceptionHandlerBootloader;
use Installer\Internal\ClassMetadataInterface;
use Installer\Internal\ReflectionClassMetadata;
use Spiral\Http\Middleware\ErrorHandlerMiddleware\EnvSuppressErrors;
use Spiral\Http\Middleware\ErrorHandlerMiddleware\SuppressErrorsInterface;
use Spiral\Reactor\Writer;

final class ExceptionHandlerBootloaderConfigurator extends BootloaderConfigurator
{
    public function __construct(
        Writer $writer,
        ClassMetadataInterface $class = new ReflectionClassMetadata(ExceptionHandlerBootloader::class),
    ) {
        parent::__construct($class, $writer);

        $this->addBinding(SuppressErrorsInterface::class, EnvSuppressErrors::class);
    }

    public function addBinding(string $alias, string $resolver): void
    {
        $this->append('BINDINGS', new ClassBinding($alias, $resolver));
    }
}
