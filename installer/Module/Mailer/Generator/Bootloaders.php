<?php

declare(strict_types=1);

namespace Installer\Module\Mailer\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\SendIt\Bootloader\MailerBootloader;

final class Bootloaders implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(MailerBootloader::class);

        $context->kernel->load->addGroup(
            bootloaders: [
                MailerBootloader::class,
            ],
            comment: 'Mailer',
            priority: 14
        );
    }
}
