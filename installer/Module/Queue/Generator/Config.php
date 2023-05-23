<?php

declare(strict_types=1);

namespace Installer\Module\Queue\Generator;

use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Spiral\Queue\Bootloader\QueueBootloader;

final class Config implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->addUse(QueueBootloader::class);
        $context->kernel->load->addGroup(
            bootloaders: [
                QueueBootloader::class,
            ],
            comment: 'Queue',
            priority: 13
        );

        $context->application->useRoadRunnerPlugin('jobs');

        $context->resource->copy('config', 'app/config');

        $context->envConfigurator->addGroup(
            values: [
                'QUEUE_CONNECTION' => 'sync',
            ],
            comment: 'Queue',
            priority: 6
        );
    }
}
