<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Bootloader;

use App\Application\Bootloader\AppBootloader;
use Spiral\Core\CoreInterface;
use Spiral\Reactor\Writer;

final class DomainInterceptorsConfigurator extends BootloaderConfigurator
{
    public function __construct(Writer $writer, string $class = AppBootloader::class)
    {
        parent::__construct($class, $writer);

        $this->append('SINGLETONS', new ClassMethodBinding(CoreInterface::class, 'domainCore'));
    }

    public function addInterceptor(string $class): void
    {
        $this->append('INTERCEPTORS', new ClassName($class));
    }
}
