<?php

declare(strict_types=1);

namespace Installer\Module\SapiBridge\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Bootloader\Http\PaginationBootloader;
use Spiral\Sapi\Bootloader\SapiBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(SapiBootloader::class);

        $context->kernel->load->append(SapiBootloader::class, PaginationBootloader::class);
    }
}
