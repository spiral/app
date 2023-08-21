<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Mailer\Generator\Bootloaders;
use Installer\Module\Mailer\Generator\Config;
use Spiral\SendIt\Bootloader\MailerBootloader;

final class Mailer extends AbstractModule
{
    public function getGenerators(ApplicationInterface $application): array
    {
        return [
            Bootloaders::class,
            Config::class,
        ];
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            MailerBootloader::class,
        ];
    }

    public function getCopiedResources(ApplicationInterface $application): array
    {
        return ['config' => 'app/config'];
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        return [
            'MAILER_DSN' => null,
            'MAILER_QUEUE' => 'local',
            'MAILER_QUEUE_CONNECTION' => null,
            'MAILER_FROM' => '"Spiral <sendit@local.host>"',
        ];
    }
}
