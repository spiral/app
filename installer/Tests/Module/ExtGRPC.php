<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Module\RoadRunnerBridge\GRPC\ExtGRPC as ExtGRPCPackage;

final class ExtGRPC extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new ExtGRPCPackage());
    }
}
