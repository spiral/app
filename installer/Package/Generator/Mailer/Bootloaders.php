<?php

declare(strict_types=1);

namespace Installer\Package\Generator\Mailer;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
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
            priority: 13
        );
    }
}
