<?php

declare(strict_types=1);

namespace Installer\Component\Generator\Queue;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
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

        $context->resource->copy('components/queue/config', 'app/config');

        $context->envConfigurator->addGroup(
            values: [
                'QUEUE_CONNECTION' => 'sync',
            ],
            comment: 'Queue',
            priority: 6
        );
    }
}