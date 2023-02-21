<?php

declare(strict_types=1);

namespace Installer\Package\Generator\Queue;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Spiral\Queue\Bootloader\QueueBootloader;

final class Config implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->kernel->load->append(QueueBootloader::class);

        $context->application->useRoadRunnerPlugin('jobs');

        $context->resource->copy('packages/queue/config', 'app/config');
    }
}