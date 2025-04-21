<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Bootloader;

use App\Application\Bootloader\AppBootloader;
use Installer\Internal\ClassMetadataInterface;
use Installer\Internal\ReflectionClassMetadata;
use Spiral\Interceptors\HandlerInterface;
use Spiral\Reactor\Writer;

final class DomainInterceptorsConfigurator extends BootloaderConfigurator
{
    public function __construct(
        Writer $writer,
        ClassMetadataInterface $class = new ReflectionClassMetadata(AppBootloader::class),
    ) {
        parent::__construct($class, $writer);

        $this->append('SINGLETONS', new ClassMethodBinding(HandlerInterface::class, 'domainCore'));
    }

    public function addInterceptor(string $class): void
    {
        $this->append('INTERCEPTORS', new ClassName($class));
    }
}
