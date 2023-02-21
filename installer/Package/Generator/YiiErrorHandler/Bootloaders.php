<?php

declare(strict_types=1);

namespace Installer\Package\Generator\YiiErrorHandler;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\YiiErrorHandler\Bootloader\YiiErrorHandlerBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(YiiErrorHandlerBootloader::class);

        $context->kernel->load->append(YiiErrorHandlerBootloader::class, MonologBootloader::class);
    }
}
